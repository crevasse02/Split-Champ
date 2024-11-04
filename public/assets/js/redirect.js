const redirectScript = `
  (function () {
    const script = document.createElement("script");
    script.src = "https://cdn.jsdelivr.net/gh/crevasse02/testing-js@latest/redirect2.js";
    script.async = true;
    document.head.appendChild(script);
})()
`;
