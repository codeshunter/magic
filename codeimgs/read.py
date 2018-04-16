import pytesseract
from PIL import Image

image = Image.open('2.jpg')
vcode = pytesseract.image_to_string(image)
print (vcode)