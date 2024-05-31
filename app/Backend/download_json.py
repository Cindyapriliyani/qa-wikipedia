import os
import requests

# URL yang valid untuk file JSON
url = 'https://raw.githubusercontent.com/rajpurkar/SQuAD-explorer/master/dataset/dev-v2.0.json'
# Path absolut untuk menyimpan file JSON yang didownload
file_path = 'D:/Cendol/KULIAH/MATA KULIAH S6/TUGAS AKHIR/new/app/Backend/dev-v2.0.json'

# Pastikan direktori ada
os.makedirs(os.path.dirname(file_path), exist_ok=True)

try:
    # Mengirim permintaan GET untuk mendownload file JSON
    response = requests.get(url)
    # Memastikan permintaan berhasil (status code 200)
    response.raise_for_status()  # Memicu error untuk status kode 4xx/5xx
    # Menyimpan isi file JSON ke lokasi yang ditentukan
    with open(file_path, 'w') as f:
        f.write(response.text)
    print(f"File JSON telah disimpan di {file_path}")
except requests.exceptions.HTTPError as http_err:
    print(f"HTTP error occurred: {http_err}")
except requests.exceptions.ConnectionError as conn_err:
    print(f"Connection error occurred: {conn_err}")
except requests.exceptions.Timeout as timeout_err:
    print(f"Timeout error occurred: {timeout_err}")
except requests.exceptions.RequestException as req_err:
    print(f"An error occurred: {req_err}")
except Exception as e:
    print(f"An error occurred while writing the file: {e}")
