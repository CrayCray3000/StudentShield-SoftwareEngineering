import pandas as pd
import random
import os

print("Initializing high-accuracy Gen-Z/Junior High dataset generation...")
print("Coverage: Bullying, Self-Harm, Depression, Anxiety, Loneliness, Family Crisis, Academic Distress")

# ══════════════════════════════════════════════════════════════════════
# COMPREHENSIVE EMOTIONAL DISTRESS SEED DATA
# StudentShield detects EMOTIONAL DISTRESS in student vents, not just
# bullying.  Class 1 = genuine distress / crisis.  Class 0 = normal
# venting, sarcasm, hyperbole, everyday student life.
# ══════════════════════════════════════════════════════════════════════

seed_data = [
    # ════════════════════════════════════════════════════════════════
    # CLASS 1: EMOTIONAL DISTRESS (Genuine crisis signals)
    # ════════════════════════════════════════════════════════════════

    # ── Self-Harm / Suicidal Ideation ────────────────────────────
    ("gusto ko na mamatay, di ko na kaya", 1),
    ("pagod na pagod na ako sa buhay ko, ayoko na talaga", 1),
    ("wala akong kwenta, pabigat lang ako sa pamilya ko", 1),
    ("magbibigti na lang siguro ako para matapos na to lahat", 1),
    ("sobrang bigat ng nararamdaman ko, di ko na kaya mabuhay", 1),
    ("suko na ko sa buhay. tatalon na lang ako sa building.", 1),
    ("literal na gusto ko na magpakamatay dahil sa ginawa nila sakin", 1),
    ("hirap na hirap na ako lord kunin mo na ako please", 1),
    ("ayoko na sa mundong ito, gusto ko na mawala", 1),
    ("wala na pag asa tong buhay ko, useless ako", 1),
    ("araw araw na lang akong umiiyak, gusto ko na tapusin to", 1),
    ("I think I'm really going to do it tonight. Goodbye.", 1),
    ("I just want to end myself, everything is too hard.", 1),
    ("I am going to end my life tonight. I can't take it.", 1),
    ("I'm standing at the edge of the roof and I want to jump so badly.", 1),
    ("nagssself-harm na ako, diko mapigilan yung sarili ko", 1),
    ("hinahiwa ko na braso ko kasi yun lang ang nakakapagpagaan ng loob ko", 1),
    ("gusto nako mamatay tinuod ni, di nako kaya", 1),
    ("wala nako kabalo unsay buhaton, mas maayo pa mamatay na lang ko", 1),
    ("wala koy gamit, kalas lang ko sa kalibutan, lami ihikog", 1),
    ("kalami ra e hikog oy, diko kaantos sa ilang ginabuhat", 1),
    ("kayat ko matayen, di ko kaya daytoy", 1),
    ("awan pakairananko dtoy lubong, nasaysayaat nu matay nak", 1),

    # ── Depression / Hopelessness ────────────────────────────────
    ("ang bigat bigat ng pakiramdam ko, parang walang kwenta lahat", 1),
    ("ilang araw na akong di kumakain, wala akong gana sa lahat", 1),
    ("di na ko natutulog ng maayos, lagi akong umiiyak sa gabi", 1),
    ("parang empty na lang ako, wala na akong nararamdaman", 1),
    ("everyday feels like a struggle, I can't find any reason to keep going", 1),
    ("I haven't felt happy in months. Nothing brings me joy anymore.", 1),
    ("I feel so empty inside, like nothing matters anymore", 1),
    ("Everything is grey. I just exist, I don't live.", 1),
    ("I can't remember the last time I smiled and actually meant it", 1),
    ("nawalan na ko ng motivation sa lahat, pati sa mga bagay na dati naeenjoy ko", 1),
    ("parang zombie na lang ako sa school, walang buhay", 1),
    ("sobrang drained ko mentally, para na akong patay sa loob", 1),
    ("gikapoy nako sa tanan, gusto nako muundang sa kinabuhi", 1),
    ("hunongon na nako ni, di nako makaya ang kasakit", 1),
    ("mabannogakon ti panagbiag ko, awan serserbi na", 1),
    ("I wake up every morning wishing I didn't", 1),
    ("walang point mag-try, walang mangyayari sa buhay ko kahit anong gawin ko", 1),
    ("I feel like a burden to everyone around me", 1),
    ("sobrang lungkot ko na parang may butas sa dibdib ko", 1),
    ("I've been crying every single night for two weeks straight", 1),

    # ── Anxiety / Panic ─────────────────────────────────────────
    ("di ko na kaya yung anxiety ko, parang mamamatay na ako sa kaba", 1),
    ("sobrang takot ko sa lahat, di ko na alam gagawin ko", 1),
    ("may panic attack ako kanina sa classroom, di ako makahinga", 1),
    ("I can't stop shaking, my heart is racing and I feel like I'm dying", 1),
    ("I'm so anxious I threw up before going to school today", 1),
    ("nagkaka-panic attack na ko araw araw, sobrang hirap", 1),
    ("I can't breathe properly anymore when I think about school", 1),
    ("natatakot akong pumunta sa school, diko alam bakit pero grabe yung takot", 1),
    ("my hands won't stop trembling, I feel like I'm going crazy", 1),
    ("lagi akong kinakabahan, parang may masama lagi mangyayari", 1),

    # ── Loneliness / Isolation ──────────────────────────────────
    ("wala akong kahit isang kaibigan, sobrang alone ko", 1),
    ("nobody talks to me at school, I eat lunch alone every single day", 1),
    ("parang invisible ako sa klase, walang pumapansin sakin", 1),
    ("I have no one to talk to, I'm completely alone in this", 1),
    ("lahat ng kaibigan ko iniwan na ko, wala na akong matatakbuhan", 1),
    ("I sit alone during group activities because no one wants to be with me", 1),
    ("walang may pake sakin kahit sa bahay, lagi akong mag-isa", 1),
    ("everyone has their friend groups and I'm just the one left out", 1),
    ("sobrang sakit maging invisible, parang wala akong halaga sa kahit kanino", 1),
    ("I feel so disconnected from everyone, like I'm living behind glass", 1),

    # ── Bullying / Violence ─────────────────────────────────────
    ("sinasaktan nila ako araw araw sa school", 1),
    ("binugbog nila ako sa likod ng gym kanina", 1),
    ("pinahiya nila ako sa buong klase, feeling ko mamatay ako sa hiya", 1),
    ("tinatrato nila akong parang aso, ayoko na pumasok", 1),
    ("inaabangan nila ako sa labas ng classroom araw-araw para asarin", 1),
    ("kinukuha nila lagi baon ko natatakot akong lumaban kasi madami sila", 1),
    ("hinahagis nila gamit ko sa basurahan ng room, tapos tinatawanan ako", 1),
    ("natatakot akong pumasok, binubugbog nila ako sa cr ng building natin", 1),
    ("pinagbabantaan nila akong papatayin pag nagsumbong ako sa principal", 1),
    ("sinasapasapak nila ako pag walang teacher sa loob ng room", 1),
    ("They cornered me in the hallway again and hit me until I bled.", 1),
    ("Someone threatened to stab me after school today.", 1),
    ("They stabbed him with a pencil during class and no one did anything.", 1),
    ("gi atangan ko nila gawas sa classroom para kulatahon", 1),
    ("gikataw-an ko nila sa canteen nag hilak ko sa cr, gusto nako mawala", 1),
    ("in-inut dak a patpatayen dagiti kaklasek", 1),

    # ── Cyberbullying / Online Harassment ────────────────────────
    ("may gumawa ng fake account ko at nagkakalat ng chismis about me", 1),
    ("pinopost nila yung picture ko sa gc para pagawanan, sobrang nakakasakit", 1),
    ("they keep sending me death threats on messenger", 1),
    ("may group chat sila kung saan lagi nila akong pinagtitripan", 1),
    ("someone leaked my private photos at school, I want to disappear", 1),
    ("pinagsasabihan nila akong pangit at walang kwenta sa comments ng socmed ko", 1),
    ("they made a hate page about me on facebook", 1),
    ("lagi nila akong finoforward sa gc tapos tinatawanan", 1),

    # ── Family Problems / Abuse ─────────────────────────────────
    ("sinasaktan ako ng tatay ko pag lasing siya", 1),
    ("lagi silang nag-aaway ng nanay at tatay ko, natatakot na ako umuwi", 1),
    ("sinasabihan akong walang kwenta ng sarili kong magulang", 1),
    ("my parents keep telling me I'm worthless and a mistake", 1),
    ("my dad hits me when he's drunk, I don't feel safe at home", 1),
    ("nagtatago ako sa kwarto ko kasi nag-aaway nanaman sila, takot na takot na ko", 1),
    ("I wish I was never born, my family doesn't want me here", 1),
    ("pinalayas ako ng nanay ko, wala akong pupuntahan", 1),
    ("my mom said she wishes I was never born", 1),
    ("ang toxic ng bahay namin, mas gusto ko pa matulog sa school kesa umuwi", 1),
    ("lagi akong sinisigawan sa bahay, feeling ko walang nagmamahal sakin", 1),
    ("my parents are getting divorced and they both blame me for it", 1),

    # ── Academic Crisis with Emotional Breakdown ────────────────
    ("bagsak na bagsak na ako sa lahat ng subjects, gusto ko na mag-drop out", 1),
    ("I'm failing everything and I don't see any point in trying anymore", 1),
    ("sobrang pressure sakin ng parents ko sa grades, di ko na kaya", 1),
    ("I got expelled and my parents will kill me, I don't know what to do", 1),
    ("napagalitan ako ng teacher sa harap ng lahat, sobrang nahiya ako gusto ko na lang mawala", 1),
    ("my parents will disown me when they see my grades, I'm so scared", 1),
    ("I can't do anything right, I keep failing no matter how hard I try", 1),
    ("pumapasok pa ko pero wala na akong naiintindihan, para na lang akong pader sa classroom", 1),

    # ── Relationship / Heartbreak with Crisis ───────────────────
    ("iniwan ako ng jowa ko for my best friend, gusto ko na lang mamatay", 1),
    ("I caught my boyfriend cheating and I feel like my world just ended", 1),
    ("sobrang sakit, di ko na kaya. wala na akong reason para manatili", 1),
    ("pinagpalit ako sa iba, ang worthless ko talaga", 1),

    # ════════════════════════════════════════════════════════════════
    # CLASS 0: NORMAL VENTING (Sarcasm, hyperbole, everyday student life)
    # ════════════════════════════════════════════════════════════════

    # ── Academic Stress (Normal) ────────────────────────────────
    ("nakakapagod mag-aral pero sige lang para sa grades", 0),
    ("ang hirap ng exam sirang sira ulo ko kay sir", 0),
    ("nangamote ako sa math quiz natin kanina, wala na bagsak na", 0),
    ("gusto ko na umuwi, antok na antok na ko sa lecture ni maam", 0),
    ("gusto ko na mag-resign as a student lmao", 0),
    ("suko na ko sa math, bahala na si batman", 0),
    ("wala na, finish na ang grades ko this sem dahil sa physics", 0),
    ("This homework is literally killing me.", 0),
    ("Just studying for the math exam tonight. Pray for me.", 0),
    ("I got a B+ on my essay, which is okay but I'm crying inside.", 0),
    ("ang hirap ng project sa TLE, hindi ko alam pano gawin", 0),
    ("I stayed up all night studying and I still feel unprepared", 0),
    ("grabe yung workload ngayong grading, daming activities", 0),
    ("hindi ko pa nagagawa homework ko sa math, bahala na bukas", 0),
    ("report namin bukas di pa ko handa pero kaya pa naman", 0),
    ("ang boring ng lecture ni sir kanina, nakatulog ako haha", 0),
    ("atay kalisod sa exam, makabuang", 0),
    ("narigat jay exam me tatta, makapa-sakit ulo", 0),
    ("need ko mag-review tonight or else talo ako sa quiz bukas", 0),
    ("nakaka-stress yung group project kasi di kumikilos yung iba", 0),

    # ── Gen-Z Sarcasm / Hyperbole ───────────────────────────────
    ("mamatay ako sa kakatawa sa ginawa mo gago hahaha", 0),
    ("depressed na depressed ako sa grades ko charot lang bawi next quarter", 0),
    ("laslas na kase bagsak sa exam lol 💀", 0),
    ("yung grade 9 na crush ko di ako pinansin, deds na me 😭", 0),
    ("grabe yung reporting literal na pinatay ako sa kaba shuta", 0),
    ("naiiyak na ko sa thesis gusto ko na lang maging hatdog", 0),
    ("ang lala ng pe prof natin, mamamatay na ata ako sa pagod sa pushups", 0),
    ("kms pagod na me mag-aral gusto ko na lang yumaman ng walang ginagawa", 0),
    ("sira na mental health ko dahil sa project na to charot kaya pa", 0),
    ("kamote talaga yung kaklase kong maingay, sarap sapakin char", 0),
    ("deds ako sa kakatawa sayo kanina sa canteen", 0),
    ("ayoko na mag-aral, gusto ko na lang maging pusa meow", 0),
    ("I died laughing at that joke lmao 😂", 0),
    ("Kms after this math test it was so hard lol", 0),
    ("bruh this math assignment is making me want to jump out the window fr", 0),
    ("omg the canteen line was so long i thought i was gonna die starving", 0),
    ("maam stop with the surprise quizzes im gonna end myself rn 💀", 0),
    ("literally dying rn because my crush looked at me", 0),
    ("I finally finished that history project. I can rest in peace.", 0),
    ("rip sa grades ko this grading 😭", 0),
    ("nakakamatay yung init sa quadrangle kanina flag ceremony", 0),
    ("mamatay kos kinatawa ani niya, animal", 0),
    ("gikapoy nako sigeg tuon lami na iundang", 0),
    ("mabannogakon nga agadal, kayat ko maturug", 0),
    ("ang gagaling niyo palang artista? sana sa school theater na lang kayo sumali char", 0),
    ("salamat sa feedback niyo ha? hindi ko alam na board of judges pala kayo ng buhay ko lol", 0),

    # ── Social / Friendship (Normal) ────────────────────────────
    ("my social battery is dead, leave me alone tbh", 0),
    ("ang gulo ng barkada namin pero love ko pa rin sila haha", 0),
    ("nag-away kami ng best friend ko pero okay na kami bukas for sure", 0),
    ("ang dami kong chismis na narinig sa school today lol", 0),
    ("nakakairita yung seatmate ko pero chill lang", 0),
    ("nagtatampo yung tropa ko kasi di ko siya sinagot agad sa chat", 0),
    ("I'm not going to the party this weekend, too tired from school", 0),
    ("my friends are so funny I can't with them 😂", 0),
    ("ang ingay ng mga classmate ko pero masaya naman", 0),
    ("nagkita kami ng elementary friends ko kanina, ang saya", 0),

    # ── Everyday Life / Neutral ─────────────────────────────────
    ("nakakaiyak yung presyo ng pagkain sa canteen, ubos baon ko", 0),
    ("lami e-kaog lami ron, gigutom nako sa klase", 0),
    ("atay kapoyag skwela oy, gusto nako madato", 0),
    ("makahilak man sad tag popcorn ani nga salida", 0),
    ("ang init ngayon sa school, pati utak ko natutunaw", 0),
    ("excited na ko sa weekend, pupunta kami sa mall", 0),
    ("gutom na gutom na ko, ang tagal ng lunch break", 0),
    ("ang bilis ng araw, friday na naman bukas", 0),
    ("natulog ako sa klase kanina, nakatayo pa si sir", 0),
    ("ang saya ng field trip namin kahapon, best day ever", 0),
    ("kinain ko lahat ng baon ko before lunch haha", 0),
    ("ang traffic kanina papasok, halos late na ko", 0),
    ("ang lamig sa classroom namin, parang freezer", 0),
    ("naka-uniform pa rin ako hanggang ngayon, tamad magbihis", 0),
    ("ang ganda ng sunset kanina habang naghihintay ng jeep", 0),

    # ── Crush / Romance (Normal) ────────────────────────────────
    ("kinikilig ako kanina kasi tumingin yung crush ko sakin", 0),
    ("ang cute ng new student sa kabilang section omg", 0),
    ("nireject niya ko pero okay lang, marami pa naman dyan haha", 0),
    ("I have a crush on someone but I'm too shy to talk to them lol", 0),
    ("ang sweet ng jowa ko, dinalhan ako ng food sa school", 0),
    ("break kami ng jowa ko pero move on na ko charot di pa pala 😅", 0),

    # ── Positive / Hopeful ──────────────────────────────────────
    ("challenging yung araw na to pero survive naman", 0),
    ("kahit mahirap, kakayanin ko to para sa future ko", 0),
    ("grateful ako sa mga friends ko, sila yung reason ko para mag-try", 0),
    ("I had a bad day but tomorrow is a new start", 0),
    ("things are tough right now but I know it'll get better", 0),
    ("napasa ko yung exam na akala ko babagsak ako, sana lagi", 0),
    ("finally natapos ko na yung project, ang sarap sa feeling", 0),
]

def generate_dataset(total_samples=4000):
    """Generate balanced, leak-free train/val splits with augmentation."""
    print("Initializing Advanced Data Augmenter for High Accuracy...")
    
    def augment_text(text):
        """Apply random augmentation while preserving meaning."""
        words = text.split()
        if len(words) <= 3:
            return text
        
        aug_type = random.choice(['drop', 'swap', 'repeat', 'typo', 'identity'])
        if aug_type == 'drop':
            # Drop a random non-critical word
            drop_idx = random.randint(0, len(words) - 1)
            return " ".join(words[:drop_idx] + words[drop_idx+1:])
        elif aug_type == 'swap':
            # Swap two adjacent words
            idx = random.randint(0, len(words) - 2)
            words[idx], words[idx+1] = words[idx+1], words[idx]
            return " ".join(words)
        elif aug_type == 'repeat':
            # Repeat a random word (common in emotional texts)
            idx = random.randint(0, len(words) - 1)
            words.insert(idx, words[idx])
            return " ".join(words)
        elif aug_type == 'typo':
            # Simulate common typos (missing letters, doubled letters)
            idx = random.randint(0, len(words) - 1)
            word = words[idx]
            if len(word) > 3:
                char_idx = random.randint(1, len(word) - 2)
                if random.random() < 0.5:
                    # Delete a character
                    word = word[:char_idx] + word[char_idx+1:]
                else:
                    # Double a character
                    word = word[:char_idx] + word[char_idx] + word[char_idx:]
                words[idx] = word
            return " ".join(words)
        else:
            return text

    def generate_split(seeds, num_samples, filename):
        dataset = []
        # Add original seeds first
        for text, label in seeds:
            dataset.append({"text": text, "label": label})
            
        counts = {0: 0, 1: 0}
        for item in dataset:
            counts[item["label"]] += 1
            
        target_per_class = num_samples // 2
        
        # Track existing texts to avoid duplicates
        existing_texts = set(d["text"] for d in dataset)
        
        # Augment until target is met
        retries = 0
        max_retries = num_samples * 10
        while (counts[0] < target_per_class or counts[1] < target_per_class) and retries < max_retries:
            seed_idx = random.randint(0, len(seeds) - 1)
            text, label = seeds[seed_idx]
            
            if counts[label] < target_per_class:
                augmented_text = augment_text(text)
                if augmented_text not in existing_texts:
                    dataset.append({"text": augmented_text, "label": label})
                    existing_texts.add(augmented_text)
                    counts[label] += 1
                else:
                    retries += 1
            else:
                retries += 1
                    
        random.shuffle(dataset)
        df = pd.DataFrame(dataset)
        
        os.makedirs('data', exist_ok=True)
        df.to_csv(filename, index=False)
        print(f"Saved {len(df)} samples to {filename} (Class 0: {counts[0]}, Class 1: {counts[1]})")

    # STRUCTURAL FIX: Split the seeds BEFORE augmentation to prevent Train/Val leakage
    random.seed(42)
    shuffled_seeds = seed_data.copy()
    random.shuffle(shuffled_seeds)
    
    # 80/20 Seed Split
    split_idx = int(len(shuffled_seeds) * 0.8)
    train_seeds = shuffled_seeds[:split_idx]
    val_seeds = shuffled_seeds[split_idx:]
    
    # Allocate samples (80% train, 20% val)
    train_target = int(total_samples * 0.8)
    val_target = total_samples - train_target
    
    print(f"\nTotal seeds: {len(seed_data)} (Class 0: {sum(1 for _,l in seed_data if l==0)}, Class 1: {sum(1 for _,l in seed_data if l==1)})")
    print(f"Train seeds: {len(train_seeds)}, Val seeds: {len(val_seeds)}")
    
    print("\nGenerating Train Split...")
    generate_split(train_seeds, train_target, 'data/train_vents.csv')
    
    print("\nGenerating Validation Split...")
    generate_split(val_seeds, val_target, 'data/val_vents.csv')
    
    print("\nSuccessfully generated leak-free synthetic datasets.")

if __name__ == "__main__":
    generate_dataset(4000)
