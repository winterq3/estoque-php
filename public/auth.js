async function protegerPagina() {
  try {
    const res = await fetch('auth/me');
    if (!res.ok) {
      window.location.href = 'login.html';
      return;
    }
    const usuario = await res.json();
    const nomeEl = document.getElementById('usuario-logado');
    if (nomeEl) nomeEl.textContent = usuario.nome;
  } catch {
    window.location.href = 'login.html';
  }
}

async function fazerLogout() {
  await fetch('auth/logout', { method: 'POST' });
  window.location.href = 'login.html';
}

protegerPagina();