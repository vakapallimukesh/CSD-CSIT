import os
from PIL import Image

src_dir = r"C:\Users\HP\.gemini\antigravity-ide\brain\5a72bb9e-7f85-4bda-9893-b356de8cbe21\.user_uploaded"
dest_dir = r"c:\Users\HP\Downloads\DEPARTMENT-CSD & CSIT\CSD-CSIT\images\placements"
os.makedirs(dest_dir, exist_ok=True)

# 1-to-1 poster single student face crops
single_students = [
    ("media_1787215571531.png", "2291A6243.jpg"), # P Rama Ganesh
    ("media_1787215582827.png", "22B91A6203.jpg"), # B Lakshman
    ("media_1787215594759.png", "22B91A6255.jpg"), # V Swapanth
    ("media_1787215628011.png", "22B91A6246.jpg"), # P Vamsi
    ("media_1787215639179.png", "22B91A6237.jpg"), # P Nikhil
    ("media_1787216166550.png", "22B91A6259.jpg"), # V Manasa
    ("media_1787216195235.png", "22B91A6206.jpg"), # B Tejesh
    ("media_1787216209182.png", "23B95A6207.jpg"), # Tanguturi Pavansai
]

for src_name, out_name in single_students:
    img_path = os.path.join(src_dir, src_name)
    if os.path.exists(img_path):
        img = Image.open(img_path)
        w, h = img.size
        # Single poster right-side photo frame: left=0.58*w, top=0.38*h, right=0.96*w, bottom=0.78*h
        crop_box = (int(0.58 * w), int(0.38 * h), int(0.96 * w), int(0.78 * h))
        cropped = img.crop(crop_box)
        cropped.convert("RGB").save(os.path.join(dest_dir, out_name), quality=95)
        print(f"Extracted face for {out_name}")

# Group Poster 1: Aunix (media_1787216237346.png)
aunix_path = os.path.join(src_dir, "media_1787216237346.png")
if os.path.exists(aunix_path):
    img = Image.open(aunix_path)
    w, h = img.size
    # 5 circles:
    # Row 1 (top): M Tanmay (left), Ch. Shanmukha (center), K Prem (right)
    # Row 2 (bottom): K Dolly Ganya (left-center), N Likhitha (right-center)
    aunix_crops = [
        ("23B91A6240.jpg", (int(0.14 * w), int(0.31 * h), int(0.35 * w), int(0.53 * h))), # M Tanmay
        ("23B91A6214.jpg", (int(0.39 * w), int(0.31 * h), int(0.61 * w), int(0.53 * h))), # Ch Shanmukha
        ("23B91A6234.jpg", (int(0.64 * w), int(0.31 * h), int(0.86 * w), int(0.53 * h))), # K Prem
        ("23B91A6230.jpg", (int(0.24 * w), int(0.60 * h), int(0.46 * w), int(0.82 * h))), # K Dolly Ganya
        ("23B91A6248.jpg", (int(0.53 * w), int(0.60 * h), int(0.75 * w), int(0.82 * h))), # N Likhitha
    ]
    for out_name, box in aunix_crops:
        cropped = img.crop(box)
        cropped.convert("RGB").save(os.path.join(dest_dir, out_name), quality=95)
        print(f"Extracted group face for {out_name}")

# Group Poster 2: Zennith (media_1787216246420.png)
zennith_path = os.path.join(src_dir, "media_1787216246420.png")
if os.path.exists(zennith_path):
    img = Image.open(zennith_path)
    w, h = img.size
    zennith_crops = [
        ("23B91A0738.jpg", (int(0.19 * w), int(0.34 * h), int(0.39 * w), int(0.54 * h))), # N Leela Madhav
        ("23B91A0727.jpg", (int(0.41 * w), int(0.34 * h), int(0.61 * w), int(0.54 * h))), # K S Sriram Charan
        ("23B91A0714.jpg", (int(0.64 * w), int(0.34 * h), int(0.84 * w), int(0.54 * h))), # G Nikhila Valli
        ("23B91A6219.jpg", (int(0.33 * w), int(0.61 * h), int(0.51 * w), me := int(0.81 * h))), # G Manoj Kumar
        ("24B95A6207.jpg", (int(0.56 * w), int(0.61 * h), int(0.74 * w), int(0.81 * h))), # T Uma Sai Pavan
    ]
    for out_name, box in zennith_crops:
        cropped = img.crop(box)
        cropped.convert("RGB").save(os.path.join(dest_dir, out_name), quality=95)
        print(f"Extracted group face for {out_name}")

print("All student faces extracted and saved successfully!")
