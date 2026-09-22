function redirectToLogin() {
  const btn = document.querySelector('[data-login-url]');
  window.location.href = btn.dataset.loginUrl;
}