// Variables ටික හරියටම එක පාරක් Define කරමු
const openBtn = document.getElementById('openFormBtn');
const closeBtn = document.getElementById('closeFormBtn');
const formZone = document.getElementById('adFormContainer');
const fileInput = document.getElementById('fileInput');
const fileLabel = document.getElementById('fileLabel');

// 1. Form එක පෙන්වන Logic එක
if (openBtn) {
    openBtn.onclick = () => {
        formZone.style.display = 'block'; // Form එක පෙන්වනවා
        openBtn.style.visibility = 'hidden'; // බටන් එක හංගනවා (හැබැයි ඉඩ තියාගෙන)
        openBtn.style.opacity = '0';
        
        // උඩටම Scroll කරනවා ලස්සනට පේන්න
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
}

// 2. Form එක වහන (Abort) Logic එක
if (closeBtn) {
    closeBtn.onclick = () => {
        formZone.style.display = 'none'; // Form එක හංගනවා
        openBtn.style.visibility = 'visible'; // බටන් එක ආපහු පෙන්වනවා
        openBtn.style.opacity = '1';
    };
}

// 3. File එකක් තේරුවම පේන විදිහ
if (fileInput) {
    fileInput.onchange = function() {
        if(this.files.length > 0) {
            // පින්තූරය හරියටම Load වුණාම ලේබල් එක වෙනස් කරනවා
            fileLabel.innerHTML = "✅ CORE LOADED: " + this.files[0].name;
            fileLabel.style.color = "#00ff88"; // Neon Green
            fileLabel.style.borderColor = "#00ff88";
            fileLabel.style.boxShadow = "0 0 10px rgba(0, 255, 136, 0.2)";
        }
    };
}