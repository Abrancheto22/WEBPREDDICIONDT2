<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PredictionAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('rols')->insert([
            ['idrol' => 1, 'nombre' => 'Administrador'],
            ['idrol' => 2, 'nombre' => 'Doctor'],
            ['idrol' => 3, 'nombre' => 'Enfermera'],
            ['idrol' => 4, 'nombre' => 'Paciente'],
        ]);

        config(['services.ml.predict_url' => 'https://ml.example.test/predict']);
        config(['services.ml.api_key' => str_repeat('k', 40)]);
        config(['services.ml.age_min' => 21, 'services.ml.age_max' => 81]);
    }

    public function test_patient_cannot_confirm_a_prediction(): void
    {
        $patient = User::factory()->create(['idrol' => 4]);

        $this->actingAs($patient)
            ->post('/predicciones/guardar-confirmada', ['idcita' => 77])
            ->assertForbidden();

        $this->assertDatabaseCount('prediccion', 0);
    }

    public function test_doctor_cannot_confirm_another_doctors_appointment(): void
    {
        $doctorUser = User::factory()->create(['idrol' => 2]);
        DB::table('doctor')->insert([
            'iddoctor' => 31,
            'nombre' => 'Ficticio',
            'apellido' => 'Auditoría',
            'iduser' => $doctorUser->id,
        ]);
        DB::table('doctor')->insert([
            'iddoctor' => 99,
            'nombre' => 'Otro',
            'apellido' => 'Profesional',
            'iduser' => null,
        ]);
        DB::table('cita')->insert([
            'idcita' => 77,
            'iddoctor' => 99,
            'estado' => 'Pendiente',
        ]);

        $this->actingAs($doctorUser)
            ->post('/predicciones/guardar-confirmada', $this->clinicalInput(77))
            ->assertForbidden();

        $this->assertDatabaseCount('prediccion', 0);
        Http::assertNothingSent();
    }

    public function test_server_recalculates_probability_and_updates_only_assigned_appointment(): void
    {
        $doctorUser = User::factory()->create(['idrol' => 2]);
        DB::table('doctor')->insert([
            'iddoctor' => 31,
            'nombre' => 'Ficticio',
            'apellido' => 'Auditoría',
            'iduser' => $doctorUser->id,
        ]);
        DB::table('cita')->insert([
            'idcita' => 77,
            'iddoctor' => 31,
            'estado' => 'Pendiente',
        ]);
        Http::fake([
            'https://ml.example.test/predict' => Http::response(['probability_diabetes' => 0.2], 200),
        ]);

        $this->actingAs($doctorUser)
            ->post('/predicciones/guardar-confirmada', $this->clinicalInput(77) + [
                'probability_diabetes' => 0.99,
            ])
            ->assertRedirect(route('citas_doctores.index'));

        $this->assertDatabaseHas('prediccion', ['idcita' => 77, 'resultado' => 0.2]);
        $this->assertDatabaseHas('cita', ['idcita' => 77, 'estado' => 'Realizado']);
        Http::assertSent(fn ($request) => $request->hasHeader(
            'Authorization',
            'Bearer ' . str_repeat('k', 40)
        ));
    }

    public function test_prediction_rejects_age_outside_model_data_range(): void
    {
        $doctorUser = User::factory()->create(['idrol' => 2]);
        DB::table('doctor')->insert([
            'iddoctor' => 31,
            'nombre' => 'Ficticio',
            'apellido' => 'Auditoría',
            'iduser' => $doctorUser->id,
        ]);
        DB::table('cita')->insert([
            'idcita' => 77,
            'iddoctor' => 31,
            'estado' => 'Pendiente',
        ]);

        foreach ([20, 82] as $age) {
            $this->actingAs($doctorUser)
                ->post('/predicciones', array_replace($this->clinicalInput(77), ['edad' => $age]))
                ->assertUnprocessable()
                ->assertJsonValidationErrors('edad');
        }

        Http::assertNothingSent();
        $this->assertDatabaseCount('prediccion', 0);
    }

    public function test_prediction_age_limits_follow_configuration(): void
    {
        config(['services.ml.age_min' => 25, 'services.ml.age_max' => 75]);
        $doctorUser = User::factory()->create(['idrol' => 2]);
        DB::table('doctor')->insert([
            'iddoctor' => 31,
            'nombre' => 'Ficticio',
            'apellido' => 'Auditoría',
            'iduser' => $doctorUser->id,
        ]);
        DB::table('cita')->insert([
            'idcita' => 77,
            'iddoctor' => 31,
            'estado' => 'Pendiente',
        ]);

        foreach ([24, 76] as $age) {
            $this->actingAs($doctorUser)
                ->post('/predicciones', array_replace($this->clinicalInput(77), ['edad' => $age]))
                ->assertUnprocessable()
                ->assertJsonValidationErrors('edad');
        }

        Http::assertNothingSent();
    }

    private function clinicalInput(int $appointmentId): array
    {
        return [
            'idcita' => $appointmentId,
            'embarazos' => 1,
            'glucosa' => 99,
            'presion_sanguinea' => 70,
            'grosor_piel' => 20,
            'insulina' => 80,
            'BMI' => 25,
            'pedigree' => 0.2,
            'edad' => 30,
            'observacion' => 'dato de prueba ficticio',
        ];
    }
}
