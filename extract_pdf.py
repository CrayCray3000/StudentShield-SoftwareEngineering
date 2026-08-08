import PyPDF2
import re
import sys

def extract_pdf_info():
    try:
        reader = PyPDF2.PdfReader(r'd:\laragon\www\student shield\StudentShield Capstone.pdf')
        text = ''.join(page.extract_text() for page in reader.pages)
        
        matches = re.finditer(r'.{0,300}(?:video|conferenc|meeting|call).{0,300}', text, re.IGNORECASE | re.DOTALL)
        
        with open(r'd:\laragon\www\student shield\pdf_matches.txt', 'w', encoding='utf-8') as f:
            for m in matches:
                clean_text = m.group(0).replace('\n', ' ')
                f.write(f"---\n{clean_text}\n")
        print("Matches saved to pdf_matches.txt")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    extract_pdf_info()
