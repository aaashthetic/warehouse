import joblib
import numpy as np
from sklearn.linear_model import LinearRegression

# === Dummy dataset (month, stock) => sales ===
# month (1-12), stock level (arbitrary), sales
X = np.array([
    [1, 100],
    [2, 120],
    [3, 140],
    [4, 160],
    [5, 180],
    [6, 200],
    [7, 220],
    [8, 240],
    [9, 260],
    [10, 280],
    [11, 300],
    [12, 320],
])
y = np.array([
    110, 130, 150, 170, 200, 230,
    250, 280, 310, 340, 370, 400
])

# === Train model ===
model = LinearRegression()
model.fit(X, y)

# === Save to file ===
joblib.dump(model, "demand_model.pkl")
print("✅ demand_model.pkl has been created!")