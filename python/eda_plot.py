import requests
import matplotlib.pyplot as plt
from datetime import datetime

print("Mengambil data historis Bitcoin dari CoinGecko (30 hari)...")
url = "https://api.coingecko.com/api/v3/coins/bitcoin/market_chart"
params = {"vs_currency": "usd", "days": 30}

try:
    response = requests.get(url, params=params)
    response.raise_for_status()
    data = response.json()
    prices = data.get("prices", [])

    # Memisahkan waktu dan harga
    dates = [datetime.fromtimestamp(p[0]/1000) for p in prices]
    values = [p[1] for p in prices]

    print("Menggambar grafik...")
    # Mengatur ukuran gambar
    plt.figure(figsize=(10, 5))
    
    # Menggambar garis harga
    plt.plot(dates, values, color='#2563eb', linewidth=2)

    # Menambahkan judul dan label
    plt.title("Pergerakan Harga Historis Bitcoin (30 Hari Terakhir)", fontsize=14, fontweight='bold', pad=15)
    plt.xlabel("Tanggal", fontsize=12)
    plt.ylabel("Harga (USD)", fontsize=12)
    
    # Mempercantik tampilan
    plt.grid(True, linestyle='--', alpha=0.7)
    plt.xticks(rotation=45)
    plt.tight_layout()

    print("Menampilkan grafik! Silakan ambil SCREENSHOT dari jendela yang muncul untuk Bab 3.3.3 Anda.")
    plt.show()

except Exception as e:
    print(f"Terjadi kesalahan saat mengambil data: {e}")
