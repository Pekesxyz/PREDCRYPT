"""
PREDCRYPT - Linear Regression Prediction Script
Menggunakan Scikit-learn untuk prediksi harga cryptocurrency

Usage: python predict.py <coin_id>
Output: JSON { predicted_price, mae, rmse, historical_prices, historical_labels, prediction_prices }
"""

import sys
import json
import numpy as np
import requests
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_absolute_error, mean_squared_error
from datetime import datetime, timedelta


def fetch_historical_data(coin_id, days=30, temp_file=None, historical_data=None):
    """Ambil data historis harga dari JSON yang dikirim Laravel, atau API jika tidak ada"""
    if historical_data is not None:
        return historical_data
        
    if temp_file:
        try:
            with open(temp_file, 'r') as f:
                data = json.load(f)
                # Convert list of dicts from Laravel to list of lists [timestamp, price]
                return [[d['timestamp'], d['price']] for d in data]
        except Exception as e:
            pass # Fall back to api/dummy if file fails

    url = f"https://api.coingecko.com/api/v3/coins/{coin_id}/market_chart"
    params = {
        "vs_currency": "usd",
        "days": days
    }

    try:
        response = requests.get(url, params=params, timeout=10)
        response.raise_for_status()
        data = response.json()
        prices = data.get("prices", [])
        return prices
    except Exception as e:
        # Fallback: generate sample data
        return generate_fallback_data(coin_id, days)


def generate_fallback_data(coin_id, days):
    """Generate data fallback jika API gagal"""
    base_prices = {
        "bitcoin": 67000,
        "ethereum": 3400,
        "solana": 148,
        "binancecoin": 560,
    }
    base = base_prices.get(coin_id, 100)
    now = datetime.now()
    prices = []

    np.random.seed(42)
    current = base
    for i in range(days * 24):  # hourly data
        timestamp = (now - timedelta(hours=(days * 24 - i))).timestamp() * 1000
        change = np.random.normal(0, base * 0.005)
        current += change
        current = max(current, base * 0.8)  # prevent going too low
        prices.append([timestamp, current])

    return prices


def run_prediction(coin_id, live_price=None, temp_file=None, historical_data=None):
    """Jalankan Linear Regression pada data historis"""

    # 1. Ambil data historis
    raw_prices = fetch_historical_data(coin_id, days=30, temp_file=temp_file, historical_data=historical_data)

    if live_price is not None:
        # Memastikan titik terakhir di grafik adalah waktu saat ini (Hari ini)
        now_ms = datetime.now().timestamp() * 1000
        raw_prices.append([now_ms, float(live_price)])

    if len(raw_prices) < 10:
        return {"error": "Data historis tidak cukup untuk prediksi"}

    # 2. Siapkan data untuk model
    prices = [p[1] for p in raw_prices]
    timestamps = list(range(len(prices)))

    # Gunakan 80% data untuk training, 20% untuk testing
    split_idx = int(len(prices) * 0.8)

    X_train = np.array(timestamps[:split_idx]).reshape(-1, 1)
    y_train = np.array(prices[:split_idx])
    X_test = np.array(timestamps[split_idx:]).reshape(-1, 1)
    y_test = np.array(prices[split_idx:])

    # 3. Train model Linear Regression
    model = LinearRegression()
    model.fit(X_train, y_train)

    # 4. Evaluasi model
    y_pred_test = model.predict(X_test)
    mae = mean_absolute_error(y_test, y_pred_test)
    rmse = np.sqrt(mean_squared_error(y_test, y_pred_test))

    # 5. Prediksi harga untuk chart (7 hari ke depan)
    extra_points = 7
    step = max(1, len(prices) // 30)
    
    # Ambil sample index untuk chart historis (pastikan index terakhir selalu masuk)
    sampled_indices = list(range(0, len(prices) - 1, step))[-29:]
    sampled_indices.append(len(prices) - 1) # Selalu masukkan elemen terakhir (live price)
    
    sampled_prices = [prices[i] for i in sampled_indices]
    sampled_labels = []
    
    for i in sampled_indices:
        dt = datetime.fromtimestamp(raw_prices[i][0] / 1000)
        sampled_labels.append(dt.strftime("%d %b"))

    # Generate data Prediksi untuk 7 hari ke depan
    last_idx = len(prices) - 1
    future_X = np.array(range(last_idx, last_idx + (extra_points * step) + 1, step)).reshape(-1, 1)
    raw_future_pred = model.predict(future_X)
    
    # LOGIKA ADJUSTED INTERCEPT (BIAS): 
    # Menghitung selisih antara harga asli hari ini dengan prediksi model hari ini
    actual_last_price = prices[-1]
    model_predicted_now = raw_future_pred[0][0] if isinstance(raw_future_pred[0], (list, np.ndarray)) else raw_future_pred[0]
    bias = actual_last_price - model_predicted_now
    
    # Terapkan bias ke seluruh hasil prediksi masa depan agar garis mulus (Connected)
    future_pred = [((val[0] if isinstance(val, (list, np.ndarray)) else val) + bias) for val in raw_future_pred]

    # Harga Prediksi Final (diambil dari titik terjauh / hari ke-7)
    predicted_price = future_pred[-1]

    # Create the prediction line that starts EXACTLY at the last historical point
    prediction_for_chart = [None] * (len(sampled_prices) - 1)
    
    # The first point of prediction line is the LAST point of historical line
    prediction_for_chart.append(actual_last_price)
    
    # Append the rest of the future predictions
    for i in range(1, len(future_pred)):
        prediction_for_chart.append(future_pred[i])

    # Extra labels for prediction
    last_date = datetime.fromtimestamp(raw_prices[-1][0] / 1000)
    for i in range(extra_points):
        future_date = last_date + timedelta(days=i + 1)
        sampled_labels.append(future_date.strftime("%d %b"))

    # Pad historical data with nulls for future
    while len(sampled_prices) < len(sampled_labels):
        sampled_prices.append(None)
        
    # Ensure prediction array is same length
    while len(prediction_for_chart) < len(sampled_labels):
        prediction_for_chart.append(None)

    # 7. Harga saat ini (Gunakan live price jika ada)
    current_price = float(live_price) if live_price else prices[-1]

    return {
        "coin": coin_id,
        "current_price": round(current_price, 8),
        "predicted_price": round(float(predicted_price), 8),
        "mae": round(float(mae), 8),
        "rmse": round(float(rmse), 8),
        "change": round(((predicted_price - current_price) / current_price) * 100, 2),
        "historical_prices": [round(p, 2) if p is not None else None for p in sampled_prices],
        "prediction_prices": [round(p, 2) if p is not None else None for p in prediction_for_chart],
        "labels": sampled_labels,
        "model_info": {
            "method": "Linear Regression",
            "training_samples": len(X_train),
            "testing_samples": len(X_test),
            "coefficient": round(float(model.coef_[0]), 8),
            "intercept": round(float(model.intercept_), 8),
        }
    }


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Coin ID diperlukan. Usage: python predict.py <coin_id> [live_price] [temp_file]"}))
        sys.exit(1)

    coin_id = sys.argv[1].lower()
    live_price = sys.argv[2] if len(sys.argv) > 2 else None
    temp_file = sys.argv[3] if len(sys.argv) > 3 else None
    
    supported = ["bitcoin", "ethereum", "solana", "binancecoin"]

    if coin_id not in supported:
        print(json.dumps({"error": f"Koin '{coin_id}' tidak didukung. Pilihan: {', '.join(supported)}"}))        
        sys.exit(1)

    result = run_prediction(coin_id, live_price, temp_file)
    print(json.dumps(result))
