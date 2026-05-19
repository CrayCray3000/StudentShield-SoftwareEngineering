import pandas as pd
import random
import os
import nlpaug.augmenter.word as naw
import nltk

print("Downloading NLP Wordnet synonyms...")
try:
    nltk.download('wordnet')
    nltk.download('averaged_perceptron_tagger_eng')
    nltk.download('omw-1.4')
except Exception as e:
    print(f"NLTK Download Warning: {e}")

# Multilingual Risk Matrix: Deep synthetic generation seeds
seed_data = [
    # ---- ENGLISH (Subtle & Direct Risk) ----
    ("I just feel so tired all the time, no matter how much I sleep.", 1),
    ("Everything feels so pointless lately. I don't want to be here.", 1),
    ("I'm so exhausted I don't want to wake up tomorrow.", 1),
    ("I wish I could just disappear.", 1),
    ("No one really cares if I'm around or not. Better off dead.", 1),
    ("There is a heavy weight on my chest that won't go away.", 1),
    ("They cornered me in the hallway again and hit me.", 1),
    ("I hate them so much for doing this to me. I can't take the bullying.", 1),
    ("im too sad, its getting heavy. sometimes my mind is getting blanked.", 1),
    ("it says i should be gone in this world.", 1),
    ("i am so exhausted that i wish i would just fade away", 1),

    # ---- TAGALOG & TAGLISH (High Risk) ----
    ("gusto ko na mamatay", 1),
    ("pagod na pagod na ako sa buhay ko, ayoko na", 1),
    ("wala akong kwenta, pabigat lang ako sa pamilya", 1),
    ("magbibigti na lang siguro ako para matapos na to", 1),
    ("sobrang bigat, di ko na kaya mabuhay", 1),
    ("suko na ko. deads na kung deads.", 1),
    ("im literally gonna jump off the roof tonight.", 1),
    ("literal na gusto ko na magpakamatay dahil sa ginawa nila sakin", 1),
    ("sinasaktan nila ako araw araw sa school", 1),
    ("binugbog nila ako sa likod ng gym", 1),
    ("pinahiya nila ako sa buong klase, gusto ko na lang mawala", 1),
    ("kunin nako ni lord", 1),
    ("kunin na sana ako ni lord", 1),
    ("hirap na hirap na ako lord kunin mo na ako", 1),
    ("ayoko na sa mundong ito", 1),
    ("mawawala na ako soon", 1),

    # ---- BISAYA / CEBUANO (High Risk) ----
    ("gusto nako mamatay", 1),
    ("gikapoy nako sa tanan, gusto nako muundang", 1),
    ("wala nako kabalo unsay buhaton, mas maayo pa mamatay", 1),
    ("wala koy gamit, kalas lang ko sa kalibutan", 1),
    ("hunongon na nako ni, di nako makaya ang kasakit", 1),
    ("gi-bully ko nila kanunay, lami na kaayo maghikog", 1),

    # ---- ILOCANO (High Risk) ----
    ("kayat ko matayen", 1),
    ("mabannogakon ti panagbiag ko, awan serserbi na", 1),
    ("awan pakairananko dtoy lubong", 1),
    ("in-inut dak a patpatayen", 1),

    # ---- ENGLISH (Neutral & Sarcastic / False Positives) ----
    ("I finally finished that history project.", 0),
    ("This homework is literally killing me.", 0),
    ("I died laughing at that joke lmao 😂", 0),
    ("Kms after this math test it was so hard lol", 0),
    ("The cafeteria food was actually good today.", 0),
    ("Just studying for the math exam tonight.", 0),
    ("I got a B+ on my essay, which is okay.", 0),
    ("Trying to figure out this coding assignment.", 0),

    # ---- TAGALOG / TAGLISH (Neutral & Sarcastic / False Positives) ----
    ("nakakapagod mag-aral pero sige lang", 0),
    ("mamatay ako sa kakatawa sa ginawa mo gago", 0),
    ("depressed na depressed ako sa grades ko charot", 0),
    ("laslas na kase bagsak sa exam lol", 0),
    ("ang hirap ng exam sirang sira ulo ko", 0),
    ("nakakainis yung traffic kanina", 0),
    ("bobo ko sa math talaga haha", 0),

    # ---- BISAYA (Neutral) ----
    ("gikapoy nako sigeg tuon", 0),
    ("atay kalisod sa exam", 0),
    ("lami e-kaog lami ron", 0),

    # ---- ILOCANO (Neutral) ----
    ("mabannogakon nga agadal", 0),
    ("narigat jay exam me tatta", 0),
]

def generate_split(seeds, num_samples, aug, filename):
    dataset = []
    for text, label in seeds:
        dataset.append({"text": text, "label": label})
        
    counts = {0: 0, 1: 0}
    for item in dataset:
        counts[item["label"]] += 1
        
    target_per_class = num_samples // 2
    max_retries = 3000
    retries = 0
    
    while (counts[0] < target_per_class or counts[1] < target_per_class) and retries < max_retries:
        seed_idx = random.randint(0, len(seeds) - 1)
        text, label = seeds[seed_idx]
        
        if counts[label] < target_per_class:
            try:
                augmented = aug.augment(text)
                augmented_text = augmented[0] if isinstance(augmented, list) else augmented
                    
                if augmented_text and augmented_text not in [d["text"] for d in dataset]:
                    dataset.append({"text": augmented_text, "label": label})
                    counts[label] += 1
                else:
                    retries += 1
            except Exception:
                retries += 1
                
    random.shuffle(dataset)
    df = pd.DataFrame(dataset)
    
    os.makedirs('data', exist_ok=True)
    df.to_csv(filename, index=False)
    print(f"Saved {len(df)} samples to {filename}")

def generate_dataset(total_samples=3000):
    print("Initializing Synonym Augmenter...")
    try:
        aug = naw.SynonymAug(aug_src='wordnet', aug_p=0.4) 
    except Exception as e:
        print(f"Failed to load SynonymAug: {e}")
        return

    # STRUCTURAL FIX: Split the seeds BEFORE augmentation to prevent Train/Val leakage
    random.seed(42)
    random.shuffle(seed_data)
    
    # 80/20 Seed Split
    split_idx = int(len(seed_data) * 0.8)
    train_seeds = seed_data[:split_idx]
    val_seeds = seed_data[split_idx:]
    
    # Allocate samples (800 for train, 200 for validation)
    train_target = int(total_samples * 0.8)
    val_target = total_samples - train_target
    
    print("\nGenerating Train Split...")
    generate_split(train_seeds, train_target, aug, 'data/train_vents.csv')
    
    print("\nGenerating Validation Split...")
    generate_split(val_seeds, val_target, aug, 'data/val_vents.csv')
    
    print("\nSuccessfully generated leak-free synthetic datasets.")

if __name__ == "__main__":
    generate_dataset(1000)
