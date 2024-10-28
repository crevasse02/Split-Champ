const redirectScript = `
  setTimeout(function() {
    if (sessionStorage.getItem("hasRedirected")) {
      return;
    }

    var selectedSlug = slugs[Math.floor(Math.random() * slugs.length)];
    var tempSlugHits = localStorage.getItem(selectedSlug + "_hits") ? 
                      parseInt(localStorage.getItem(selectedSlug + "_hits")) : 0;

    tempSlugHits++;
    localStorage.setItem(selectedSlug + "_hits", tempSlugHits);

    sessionStorage.setItem("hasRedirected", "true");
    var currentUrl = window.location.origin;
    window.location.href = currentUrl + "/" + selectedSlug;
  }, 2000);
`;
