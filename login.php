<?php
session_start();
include 'db.php';

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SAMPLE login (palitan mo kung may database ka na)
    if ($username === "admin" && $password === "admin123") {
        $_SESSION['user'] = $username;
        header("Location: index.html"); // Changed from dashboard.php to index.html
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CampusHealth - Login / Signup</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: url('https://images.unsplash.com/photo-1600195077075-7c815f540a3f?auto=format&fit=crop&w=1920&q=80') 
        no-repeat center center fixed;
      background-size: cover;
    }
    .glass {
      backdrop-filter: blur(16px);
      background: rgba(255, 255, 255, 0.25);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fadeIn {
      animation: fadeIn 0.6s ease-out;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center font-sans">
  <div class="flex flex-col md:flex-row w-full max-w-5xl shadow-2xl rounded-2xl overflow-hidden fadeIn">
    <!-- Left side image panel -->
    <div class="hidden md:flex w-1/2 bg-cover bg-center"
      style="background-image: url('https://images.unsplash.com/photo-1582719478148-ef7686a79a06?auto=format&fit=crop&w=1000&q=80');">
      <div class="bg-green-800 bg-opacity-60 w-full h-full flex items-center justify-center text-white text-3xl font-bold p-6">
        <div class="text-center">
          <h2 class="text-4xl font-extrabold mb-3">CampusHealth</h2>
          <p class="text-lg font-light">Your Health, Our Priority 🌿</p>
        </div>
      </div>
    </div>

    <!-- Right side form -->
    <div class="glass w-full md:w-1/2 p-10 flex flex-col justify-center text-gray-800">
      <div class="text-center mb-6">
        <div class="w-20 h-20 mx-auto bg-green-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">❤</div>
        <h1 class="text-2xl font-semibold mt-4">CampusHealth Portal</h1>
        <p class="text-sm text-gray-600">Admin / User Access</p>
      </div>

      <div class="flex justify-center mb-6">
        <button id="loginTab" class="px-4 py-2 font-semibold border-b-2 border-green-600 text-green-600">Login</button>
        <button id="signupTab" class="px-4 py-2 font-semibold border-b-2 border-transparent text-gray-500 hover:text-green-600">Signup</button>
      </div>

      <div id="formsContainer">
        <!-- Login Form -->
        <form id="loginForm" class="flex flex-col gap-4">
          <input type="text" id="loginUsername" placeholder="Username" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400" required>
          <input type="password" id="loginPassword" placeholder="Password" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400" required>
          <button type="submit" class="bg-green-600 text-white rounded-lg py-2 font-semibold hover:bg-green-700 transition">Login</button>
        </form>

        <!-- Signup Form -->
        <form id="signupForm" class="flex flex-col gap-4 hidden">
          <input type="text" id="signupUsername" placeholder="Username" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400" required>
          <input type="email" id="signupEmail" placeholder="Email" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400" required>
          <input type="password" id="signupPassword" placeholder="Password" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400" required>
          <input type="password" id="signupConfirm" placeholder="Confirm Password" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400" required>
          <button type="submit" class="bg-green-600 text-white rounded-lg py-2 font-semibold hover:bg-green-700 transition">Signup</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const loginTab = document.getElementById('loginTab');
    const signupTab = document.getElementById('signupTab');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

    loginTab.onclick = () => {
      loginForm.classList.remove('hidden');
      signupForm.classList.add('hidden');
      loginTab.classList.add('border-green-600', 'text-green-600');
      signupTab.classList.remove('border-green-600', 'text-green-600');
    };

    signupTab.onclick = () => {
      signupForm.classList.remove('hidden');
      loginForm.classList.add('hidden');
      signupTab.classList.add('border-green-600', 'text-green-600');
      loginTab.classList.remove('border-green-600', 'text-green-600');
    };

    let users = JSON.parse(localStorage.getItem('campushealth_users')) || [];

    loginForm.onsubmit = e => {
      e.preventDefault();
      const u = loginUsername.value.trim();
      const p = loginPassword.value.trim();
      if (u === 'admin' && p === '1234') {
        alert('Welcome Admin!');
        localStorage.setItem('CH_user', JSON.stringify({ username: u })); // Aligned key
        localStorage.setItem('CH_loggedIn', 'true'); // Added
        return location.href = 'index.html';
      }
      const found = users.find(x => x.username === u && x.password === p);
      if (!found) return alert('Invalid username or password');
      localStorage.setItem('CH_user', JSON.stringify(found)); // Aligned key
      localStorage.setItem('CH_loggedIn', 'true'); // Added
      alert('Login successful!');
      location.href = 'index.html';
    };

    signupForm.onsubmit = e => {
      e.preventDefault();
      const u = signupUsername.value.trim();
      const eMail = signupEmail.value.trim();
      const p = signupPassword.value.trim();
      const c = signupConfirm.value.trim();
      if (p !== c) return alert('Passwords do not match!');
      if (users.find(x => x.username === u)) return alert('Username already exists!');
      users.push({ username: u, email: eMail, password: p });
      localStorage.setItem('campushealth_users', JSON.stringify(users));
      localStorage.setItem('CH_user', JSON.stringify({ username: u })); // Aligned key
      localStorage.setItem('CH_loggedIn', 'true'); // Added
      alert('Signup successful! Redirecting...');
      location.href = 'index.html';
    };
  </script>
</body>
</html>