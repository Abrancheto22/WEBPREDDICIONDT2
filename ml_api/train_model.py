import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.ensemble import RandomForestClassifier # Importamos RandomForestClassifier
from sklearn.metrics import accuracy_score
import joblib
import os

# Ruta al archivo CSV (asumiendo que está en la misma carpeta)
DATA_PATH = 'diabetes.csv'
MODEL_PATH = 'random_forest_model.pkl' # Nombre del archivo para el modelo Random Forest
SCALER_PATH = 'scaler.pkl'            # Nombre del archivo para el escalador

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

# 2. Preprocesamiento de datos (específico para Pima Indians Diabetes Dataset)
# Reemplazar 0s con la media en columnas específicas, ya que 0 en estas columnas son valores faltantes
# Estas columnas son las que el notebook de Kaggle original identificó como problemáticas con 0s.
columns_to_check_for_zero_replacement = ['Glucose', 'BloodPressure', 'SkinThickness', 'Insulin', 'BMI']
print("Revisando y reemplazando 0s en columnas clave...")
for column in columns_to_check_for_zero_replacement:
    if column in df.columns:
        # Solo reemplaza si hay ceros y la media no es cero
        if df[column].min() == 0 and df[column].mean() != 0:
            df[column] = df[column].replace(0, df[column].mean())
            print(f"  - Reemplazados 0s en '{column}' con la media.")
        elif df[column].min() == 0 and df[column].mean() == 0:
             print(f"  - Advertencia: Columna '{column}' contiene solo ceros, no se puede reemplazar 0s con la media.")
    else:
        print(f"  - Advertencia: La columna '{column}' no se encontró en el dataset. Salteando el reemplazo de ceros.")


# Separar características (X) y la variable objetivo (y)
# Para el Pima Indians Diabetes Dataset, la columna objetivo es 'Outcome'.
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

# 3. Dividir los datos en conjuntos de entrenamiento y prueba
X_train, X_test, y_train, y_test = train_test_split(X_scaled, y, test_size=0.2, random_state=42)
print(f"Datos divididos: Entrenamiento ({X_train.shape[0]} muestras), Prueba ({X_test.shape[0]} muestras)")

# 4. Entrenar el modelo (Random Forest Classifier)
# Puedes ajustar n_estimators (número de árboles) para mejorar el rendimiento si lo deseas.
# Un valor común es 100 o 200.
model = RandomForestClassifier(random_state=42, n_estimators=100)
model.fit(X_train, y_train)
print("Modelo de Random Forest entrenado exitosamente.")

# 5. Evaluar el modelo (opcional, para verificación)
y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)
print(f"Precisión del modelo en el conjunto de prueba: {accuracy:.4f}")

# 6. Guardar el modelo entrenado y el escalador
try:
    joblib.dump(model, MODEL_PATH)
    joblib.dump(scaler, SCALER_PATH)
    print(f"Modelo guardado en '{MODEL_PATH}'")
    print(f"Escalador guardado en '{SCALER_PATH}'")
except Exception as e:
    print(f"Error al guardar el modelo o el escalador: {e}")

print("Script de entrenamiento finalizado.")