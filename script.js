// Simulasi data pengguna
const users = [
    { username: 'admin', password: 'admin1234', role: 'admin' },
    { username: 'afia', password: 'afia1234', role: 'user' }
];

// Fungsi untuk menangani login
function handleLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    // Mencari pengguna dalam data simulasi
    const user = users.find(u => u.username === username && u.password === password && u.role === role);
    
    if (user) {
        // Mengatur cookie untuk menyimpan status login
        document.cookie = `username=${username}; role=${role}; path=/;`;
        
        // Mengarahkan ke dashboard sesuai role
        if (role === 'admin') {
            window.location.href = 'dashboard-admin.html';
        } else if (role === 'user') {
            window.location.href = 'dashboard-user.html';
        }
    } else {
        document.getElementById('error-message').innerText = 'Username atau Password salah!';
    }
}
