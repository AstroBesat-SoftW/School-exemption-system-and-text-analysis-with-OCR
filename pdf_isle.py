# -*- coding: utf-8 -*-
import sys
import os
import fitz  # PyMuPDF
import requests

API_KEY = "sk-proj-API <----"
API_KEY = API_KEY.strip()  # API key etrafındaki boşlukları kaldır

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

def pdf_to_text(pdf_path):
    doc = fitz.open(pdf_path)
    text = ""
    for page in doc:
        text += page.get_text()
    return text

def openai_metni_isle(metin):
    url = "https://api.openai.com/v1/chat/completions"
    headers = {
        "Authorization": f"Bearer {API_KEY}",
        "Content-Type": "application/json"
    }
    data = {
        "model": "gpt-3.5-turbo",
        "messages": [
            {"role": "system", "content": "You are a helpful assistant."},
            {"role": "user", "content": f"Bunu düzenle. Sadece ders isimleri, not ve akts bilgilerini al kalanını alma! ve yorum yazma ne varsa onu yaz (NOT: tablo oluştur):\n\n{metin}"}
        ],
        "max_tokens": 2000
    }
    response = requests.post(url, headers=headers, json=data)
    if response.status_code != 200:
        print(f"Hata kodu: {response.status_code}")
        print(f"Detay: {response.text}")
        return "HATA"
    return response.json()["choices"][0]["message"]["content"]

def main():
    if len(sys.argv) < 2:
        print("PDF dosya yolu eksik, sabit yol kullanılacak.")
        pdf_path = os.path.join(BASE_DIR, "uploads", "transkript.pdf")
    else:
        pdf_path = sys.argv[1]

    if not os.path.exists(pdf_path):
        print("PDF dosyası bulunamadı.")
        sys.exit(1)

    metin = pdf_to_text(pdf_path)
    sonuc = openai_metni_isle(metin)

    with open(os.path.join(BASE_DIR, "sonuc.txt"), "w", encoding="utf-8") as f:
        f.write(sonuc)

if __name__ == "__main__":
    main()
