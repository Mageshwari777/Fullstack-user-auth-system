/**
 * Shared helpers (localStorage session handling).
 * Only jQuery AJAX is used for backend communication.
 */

const STORAGE_KEY = "auth_session";

function getSession() {
  const raw = localStorage.getItem(STORAGE_KEY);
  if (!raw) return null;
  try {
    return JSON.parse(raw);
  } catch (e) {
    return null;
  }
}

function setSession(sessionObj) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(sessionObj));
}

function clearSession() {
  localStorage.removeItem(STORAGE_KEY);
}

function requireSessionOrRedirect() {
  const s = getSession();
  if (!s || !s.token) {
    window.location.href = "../html/login.html";
    return null;
  }
  return s;
}

