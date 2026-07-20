# Analytics Service (FastAPI)

Service Python untuk analisis korelasi, klaster, dan prediksi yang dipanggil oleh aplikasi Laravel.

Laravel menghubunginya lewat env `ANALYTICS_SERVICE_URL` (lihat `.env`). Default lokal: `http://127.0.0.1:8001`.

## Menjalankan di Lokal

```bash
cd python-service
python -m venv venv
venv\Scripts\activate        # Windows
# source venv/bin/activate   # Linux/Mac
pip install -r requirements.txt
python -m uvicorn main:app --host 127.0.0.1 --port 8001
```

## Menjalankan di Server Online

1. Install dependency:
   ```bash
   pip install -r requirements.txt
   ```
2. Jalankan sebagai service permanen (contoh dengan gunicorn + uvicorn worker):
   ```bash
   pip install gunicorn
   gunicorn main:app -w 2 -k uvicorn.workers.UvicornWorker -b 127.0.0.1:8001
   ```
   atau langsung uvicorn:
   ```bash
   python -m uvicorn main:app --host 127.0.0.1 --port 8001
   ```
3. Agar tetap hidup, jalankan lewat systemd / supervisor / pm2. Contoh unit systemd:
   ```ini
   [Unit]
   Description=Analytics FastAPI Service
   After=network.target

   [Service]
   WorkingDirectory=/path/ke/python-service
   ExecStart=/path/ke/venv/bin/python -m uvicorn main:app --host 127.0.0.1 --port 8001
   Restart=always

   [Install]
   WantedBy=multi-user.target
   ```
4. Set env di Laravel (`.env`) menuju URL service:
   ```
   ANALYTICS_SERVICE_URL=http://127.0.0.1:8001
   ```
   Jika service di domain/host lain, sesuaikan (mis. `https://analytics.domain.go.id`). Disarankan menaruhnya di belakang reverse proxy (Nginx) dan tidak membuka port 8001 langsung ke publik.

## Endpoint

- `GET  /` — health check
- `POST /analyze/correlation`
- `POST /analyze/cluster`
- `POST /analyze/predict`
