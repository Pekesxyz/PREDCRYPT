from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Optional, List, Dict, Any
import predict

app = FastAPI(title="PREDCRYPT API")

class PredictionRequest(BaseModel):
    coin: str
    live_price: Optional[float] = None
    historical_data: Optional[List[List[Any]]] = None # List of [timestamp, price]

@app.get("/")
def read_root():
    return {"status": "ok", "message": "PREDCRYPT API is running"}

@app.post("/predict")
def run_prediction(req: PredictionRequest):
    supported = ["bitcoin", "ethereum", "solana", "binancecoin"]
    if req.coin not in supported:
        raise HTTPException(status_code=400, detail=f"Koin '{req.coin}' tidak didukung. Pilihan: {', '.join(supported)}")
    
    try:
        result = predict.run_prediction(
            coin_id=req.coin, 
            live_price=req.live_price, 
            historical_data=req.historical_data
        )
        if "error" in result:
            raise HTTPException(status_code=400, detail=result["error"])
        return result
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
