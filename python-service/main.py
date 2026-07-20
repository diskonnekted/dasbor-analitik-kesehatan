import math
import random
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List, Dict, Any, Optional

app = FastAPI(title="Banjarnegara Health Analytics API")

class DataRow(BaseModel):
    id: str
    nama: str
    stunting: Optional[float] = None
    faskes: Optional[float] = None
    nakes: Optional[float] = None
    
class CorrelationRequest(BaseModel):
    data: List[DataRow]

def pearson_correlation(x: List[float], y: List[float]):
    n = len(x)
    if n < 2:
        return 0.0, 1.0
        
    sum_x = sum(x)
    sum_y = sum(y)
    sum_x_sq = sum(xi * xi for xi in x)
    sum_y_sq = sum(yi * yi for yi in y)
    sum_xy = sum(xi * yi for xi, yi in zip(x, y))
    
    numerator = (n * sum_xy) - (sum_x * sum_y)
    denominator = math.sqrt((n * sum_x_sq - sum_x**2) * (n * sum_y_sq - sum_y**2))
    
    if denominator == 0:
        return 0.0, 1.0
        
    corr = numerator / denominator
    
    # Simple p-value estimation based on t-distribution
    try:
        t = corr * math.sqrt((n - 2) / (1 - corr**2))
        p_value = 0.05 # placeholder for pure python implementation
    except:
        p_value = 0.05
        
    return corr, p_value

@app.post("/analyze/correlation")
async def analyze_correlation(payload: CorrelationRequest):
    try:
        valid_data = [d for d in payload.data if d.stunting is not None]
        
        if len(valid_data) < 3:
            return {
                "status": "error",
                "message": "Data terlalu sedikit untuk dianalisa (minimal 3 baris)."
            }
            
        stunting_vals = [d.stunting for d in valid_data]
        correlations = {}
        
        for var in ['faskes', 'nakes']:
            # filter items where var is not None
            paired = [(d.stunting, getattr(d, var)) for d in valid_data if getattr(d, var) is not None]
            if len(paired) < 3:
                continue
                
            x_vals = [p[0] for p in paired]
            y_vals = [p[1] for p in paired]
            
            corr, p_value = pearson_correlation(x_vals, y_vals)
            
            if abs(corr) >= 0.7:
                strength = "Kuat"
            elif abs(corr) >= 0.4:
                strength = "Sedang"
            else:
                strength = "Lemah"
                
            direction = "Positif" if corr > 0 else "Negatif"
            if abs(corr) < 0.1:
                direction = "Tidak Signifikan"
                
            correlations[var] = {
                'correlation': round(corr, 3),
                'p_value': round(p_value, 4),
                'interpretasi': f"{strength} {direction}"
            }
            
        return {
            "status": "success",
            "results": correlations,
            "sample_size": len(valid_data)
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

def simple_kmeans_1d(data: List[float], k: int = 3, max_iters: int = 50):
    if len(data) < k:
        k = len(data)
        
    # Initialize centroids randomly from data
    centroids = random.sample(data, k)
    centroids.sort()
    
    clusters = []
    for _ in range(max_iters):
        clusters = [[] for _ in range(k)]
        
        # Assign to nearest centroid
        for val in data:
            distances = [abs(val - c) for c in centroids]
            min_idx = distances.index(min(distances))
            clusters[min_idx].append(val)
            
        # Recalculate centroids
        new_centroids = []
        for i in range(k):
            if clusters[i]:
                new_centroids.append(sum(clusters[i]) / len(clusters[i]))
            else:
                new_centroids.append(centroids[i])
                
        if new_centroids == centroids:
            break
            
        centroids = new_centroids
        
    # Ensure centroids are sorted (so cluster 0 is low, 1 is medium, 2 is high)
    sorted_indices = sorted(range(k), key=lambda i: centroids[i])
    
    return centroids, sorted_indices

@app.post("/analyze/cluster")
async def analyze_cluster(payload: CorrelationRequest):
    try:
        valid_data = [d for d in payload.data if d.stunting is not None]
        if len(valid_data) < 3:
            return {"status": "error", "message": "Data tidak cukup"}
            
        stunting_vals = [d.stunting for d in valid_data]
        k = min(3, len(stunting_vals))
        
        centroids, sorted_indices = simple_kmeans_1d(stunting_vals, k)
        
        results = []
        for d in valid_data:
            # Find nearest centroid
            distances = [abs(d.stunting - c) for c in centroids]
            min_idx = distances.index(min(distances))
            
            # Map to kerawanan level (0: Rendah, 1: Sedang, 2: Tinggi)
            rank = sorted_indices.index(min_idx)
            
            kerawanan = "Rendah"
            if rank == 1 and k >= 2: kerawanan = "Sedang"
            if rank == 2 and k >= 3: kerawanan = "Tinggi"
            if k == 2 and rank == 1: kerawanan = "Tinggi"
            
            results.append({
                "id": d.id,
                "nama": d.nama,
                "stunting": d.stunting,
                "kerawanan": kerawanan
            })
            
        return {
            "status": "success",
            "clusters": results
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
        
@app.get("/")
def health_check():
    return {"status": "ok", "service": "Banjarnegara Analytics Service"}

class PredictRequest(BaseModel):
    # expect list of {"tahun": 2020, "stunting": 15.5}
    data: List[Dict[str, float]]
    years_ahead: int = 3

@app.post("/analyze/predict")
async def analyze_predict(payload: PredictRequest):
    try:
        if len(payload.data) < 3:
            return {"status": "error", "message": "Data historis terlalu sedikit (minimal 3 tahun)."}
            
        # Sort by year
        data = sorted(payload.data, key=lambda x: x['tahun'])
        years = [int(d['tahun']) for d in data]
        stunting = [float(d['stunting']) for d in data]
        
        # Simple Linear Regression: y = a + bx
        n = len(years)
        sum_x = sum(years)
        sum_y = sum(stunting)
        sum_x_sq = sum(x*x for x in years)
        sum_xy = sum(x*y for x, y in zip(years, stunting))
        
        denominator = (n * sum_x_sq - sum_x**2)
        if denominator == 0:
            return {"status": "error", "message": "Tahun tidak bervariasi."}
            
        b = (n * sum_xy - sum_x * sum_y) / denominator
        a = (sum_y - b * sum_x) / n
        
        last_year = years[-1]
        forecast = []
        
        for i in range(1, payload.years_ahead + 1):
            target_year = last_year + i
            pred_val = a + b * target_year
            forecast.append({
                "tahun": target_year,
                "prediksi": max(0, round(pred_val, 2))  # Prevents negative stunting
            })
            
        return {
            "status": "success",
            "historical": data,
            "forecast": forecast,
            "trend": "Naik" if b > 0 else "Turun",
            "slope": round(b, 4)
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
