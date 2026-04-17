import docx
import sys

sys.stdout.reconfigure(encoding='utf-8')

doc = docx.Document(r"d:\laragon\www\student shield\docs\FOLLOW THIS FORMAT.docx")
full_text = []
for para in doc.paragraphs:
    full_text.append(para.text)

rrl_index = -1
rrs_index = -1

for i, para in enumerate(full_text):
    if "Review of Related Literature" in para:
        rrl_index = i
    if "Review of Related Studies" in para:
        rrs_index = i

if rrl_index != -1:
    print("--- RRL FORMAT ---")
    for para in full_text[rrl_index:rrl_index + 20]:
        print(para)

if rrs_index != -1:
    print("\n--- RRS FORMAT ---")
    for para in full_text[rrs_index:rrs_index + 20]:
        print(para)
else:
    print("\nNo 'Review of Related Studies' section found. Searching for 'Studies'...")
    for i, para in enumerate(full_text):
        if "Studies" in para and i > rrl_index:
             print(f"Potential RRS found at paragraph {i}: {para}")
             for p in full_text[i:i+20]:
                 print(p)
             break

# Also print the first 50 paragraphs to see the general structure
print("\n--- FIRST 50 PARAGRAPHS ---")
for para in full_text[:50]:
    print(para)
