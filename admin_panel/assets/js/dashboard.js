// Real-time Clock Function
function updateClock() {
    const now = new Date();
    document.getElementById('clock').innerText = now.toLocaleTimeString();
}
setInterval(updateClock, 1000);
updateClock();

// Numbers Increment Effect (පස්සේ database එකෙන් ගමු)
function animateValue(id, start, end, duration) {
    let obj = document.getElementById(id);
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}
// Modal එක වසන ප්‍රධාන function එක
function closeModal() {
    const modal = document.getElementById("userModal");
    modal.style.opacity = "0"; // Smooth fade out effect
    setTimeout(() => {
        modal.style.display = "none";
    }, 300); // Animation එක ඉවර වුණාම hide කරනවා
}

// Modal එකෙන් පිටත click කළොත් වැසීමට
window.onclick = function(event) {
    const modal = document.getElementById("userModal");
    if (event.target == modal) {
        closeModal();
    }
}

// Keyboard එකේ 'Escape' key එක එබුවොත් වැසීමට
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeModal();
    }
});

// Modal එක පෙන්වන function එක (Update කළා)
function showUserDetails(id, name, user, email, phone, country, status, joined) {
    const modal = document.getElementById("userModal");
    const content = document.getElementById("modal-data");

    modal.style.display = "block";
    modal.style.opacity = "1"; // Fade in

    content.innerHTML = `
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="font-size: 50px; margin-bottom: 10px;">👤</div>
            <h2 style="font-family: 'Orbitron'; color: var(--primary); margin:0;">${name}</h2>
            <span style="color: var(--secondary); font-size: 12px; font-weight:bold;">CUSTOMER ID: #${id}</span>
        </div>

        <div class="info-row"><span class="info-label">USERNAME</span> <span>@${user}</span></div>
        <div class="info-row"><span class="info-label">EMAIL ADDRESS</span> <span>${email}</span></div>
        <div class="info-row"><span class="info-label">MOBILE</span> <span>${phone}</span></div>
        <div class="info-row"><span class="info-label">ORIGIN</span> <span>${country}</span></div>
        <div class="info-row"><span class="info-label">CURRENT STATUS</span> <span style="color: #00ff88; font-weight:bold;">${status}</span></div>
        <div class="info-row">
            <span class="info-label">SECURITY KEY</span> 
            <span class="pass-display">••••••••</span>
        </div>
        <div class="info-row"><span class="info-label">REGISTRATION</span> <span>${joined}</span></div>
        
        <button class="view-btn" style="width:100%; margin-top:25px; padding:15px; border-color: #ff4d4d; color: #ff4d4d;" onmouseover="this.style.background='#ff4d4d'; this.style.color='#000'" onmouseout="this.style.background='transparent'; this.style.color='#ff4d4d'" onclick="closeModal()">CLOSE TERMINAL</button>
    `;
}
