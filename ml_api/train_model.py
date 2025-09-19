import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score
import joblib
import os

# Rutas de archivos
DATA_PATH = 'diabetes.csv'
MODEL_PATH = 'random_forest_model.pkl'
SCALER_PATH = 'scaler.pkl'
OUTPUT_EXCEL_PATH = 'diabetes_modificado.xlsx'

print("Iniciando el script de entrenamiento para Pima Indians Diabetes Database (Random Forest)...")

# 1. Cargar el dataset
try:
    df = pd.read_csv(DATA_PATH)
    print(f"Dataset '{DATA_PATH}' cargado exitosamente. Filas: {df.shape[0]}, Columnas: {df.shape[1]}")
except FileNotFoundError:
    print(f"Error: El archivo '{DATA_PATH}' no se encuentra en la carpeta '{os.getcwd()}'.")
    print("Asegúrate de que 'diabetes.csv' (Pima Indians) esté en la misma carpeta que 'train_model.py'.")
    exit()
except Exception as e:
    print(f"Error al cargar el dataset: {e}")
    exit()

# --- CAMBIO DE ORDEN: 2. Reemplazar 0s con la media primero ---
columns_to_check_for_zero_replacement = ['Glucose', 'BloodPressure', 'SkinThickness', 'Insulin', 'BMI']
print("Revisando y reemplazando 0s en columnas clave...")
for column in columns_to_check_for_zero_replacement:
    if column in df.columns:
        if df[column].min() == 0 and df[column].mean() != 0:
            df[column] = df[column].replace(0, df[column].mean())
            print(f"   - Reemplazados 0s en '{column}' con la media.")
        elif df[column].min() == 0 and df[column].mean() == 0:
            print(f"   - Advertencia: Columna '{column}' contiene solo ceros, no se puede reemplazar 0s con la media.")
    else:
        print(f"   - Advertencia: La columna '{column}' no se encontró en el dataset. Salteando el reemplazo de ceros.")

# --- 3. Eliminación de datos inusuales (ahora después del reemplazo) ---
print("Eliminando datos inusuales después de reemplazar ceros...")

df.drop(df[df['Glucose'] < 70].index, inplace=True)
df.drop(df[df['Glucose'] > 500].index, inplace=True)
df.drop(df[df['BloodPressure'] < 60].index, inplace=True)
df.drop(df[df['BloodPressure'] > 180].index, inplace=True)
df.drop(df[df['Insulin'] < 30].index, inplace=True)
df.drop(df[df['Insulin'] > 200].index, inplace=True)
df.drop(df[df['BMI'] < 12].index, inplace=True)
df.drop(df[df['BMI'] > 180].index, inplace=True)
df.drop(df[df['DiabetesPedigreeFunction'] < 0.1].index, inplace=True)
df.drop(df[df['DiabetesPedigreeFunction'] > 2].index, inplace=True)
df.drop(df[df['Age'] < 18].index, inplace=True)

print(f"Datos inusuales eliminados. Nuevo tamaño del dataset: {df.shape[0]} filas.")
# -------------------------------------------------------------------------

# --- Guardar los datos modificados en un archivo de Excel ---
try:
    df.to_excel(OUTPUT_EXCEL_PATH, index=False)
    print(f"Datos preprocesados guardados en '{OUTPUT_EXCEL_PATH}'.")
except ImportError:
    print("Error: La librería 'openpyxl' no está instalada. Por favor, instálala con 'pip install openpyxl'.")
except Exception as e:
    print(f"Error al guardar el archivo Excel: {e}")
# ----------------------------------------------------------

# Separar características (X) y la variable objetivo (y)
if 'Outcome' in df.columns:
    X = df.drop('Outcome', axis=1)
    y = df['Outcome']
    print("Usando 'Outcome' como variable objetivo.")
else:
    print("Error: No se encontró la columna objetivo 'Outcome'. Asegúrate de usar el dataset Pima Indians.")
    exit()

# Escalar las características
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)
print("Características escaladas exitosamente.")

# 4. Dividir los datos en conjuntos de entrenamiento y prueba
X_train, X_test, y_train, y_test = train_test_split(X_scaled, y, test_size=0.2, random_state=42)
print(f"Datos divididos: Entrenamiento ({X_train.shape[0]} muestras), Prueba ({X_test.shape[0]} muestras)")

# 5. Entrenar el modelo
model = RandomForestClassifier(random_state=42, n_estimators=100)
model.fit(X_train, y_train)
print("Modelo de Random Forest entrenado exitosamente.")

# 6. Evaluar el modelo
y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)
print(f"Precisión del modelo en el conjunto de prueba: {accuracy:.4f}")

# 7. Guardar el modelo entrenado y el escalador
try:
    joblib.dump(model, MODEL_PATH)
    joblib.dump(scaler, SCALER_PATH)
    print(f"Modelo guardado en '{MODEL_PATH}'")
    print(f"Escalador guardado en '{SCALER_PATH}'")
except Exception as e:
    print(f"Error al guardar el modelo o el escalador: {e}")

print("Script de entrenamiento finalizado.")