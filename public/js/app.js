// ─── Sidebar toggle ────────────────────────────────────────────────
const sidebar = document.getElementById("sidebar");
const mainWrapper = document.getElementById("mainWrapper");
const menuToggle = document.getElementById("menuToggle");
const sidebarClose = document.getElementById("sidebarClose");
const sidebarOverlay = document.getElementById("sidebarOverlay");

function openSidebar() {
    sidebar?.classList.add("open");
    sidebarOverlay?.classList.add("show");
}
function closeSidebar() {
    sidebar?.classList.remove("open");
    sidebarOverlay?.classList.remove("show");
}

menuToggle?.addEventListener("click", openSidebar);
sidebarClose?.addEventListener("click", closeSidebar);
sidebarOverlay?.addEventListener("click", closeSidebar);

// Auto-close flash messages after 5s
document.querySelectorAll(".alert").forEach((alert) => {
    setTimeout(() => {
        alert.style.opacity = "0";
        alert.style.transition = "opacity .4s";
        setTimeout(() => alert.remove(), 400);
    }, 5000);
});
