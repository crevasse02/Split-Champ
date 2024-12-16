(function () {
    const apiBaseUrl = "https://split.esensigroup.com";
    const sessionKeys = new Set();

    // Normalize URL by removing query parameters and trailing slashes
    function normalizeUrl(url) {
		return url
			.trim() // Remove any leading or trailing whitespace
			.replace(/\/+$/, "") // Remove one or more trailing slashes
			.replace(/(\?.*)$/, "") // Remove query parameters
			.replace(/^https?:\/\//, "") // Remove protocol (http:// or https://)
			.replace(/^www\./, ""); // Remove 'www.' at the beginning
	}

    function fetchData(endpoint, data) {
        return fetch(`${apiBaseUrl}${endpoint}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .catch((error) => console.error(`Error in ${endpoint}:`, error));
    }

    function sendViewData(data) {
        fetchData("/view-api", data);
    }

    function sendClickData(data) {
        fetchData("/tracker-api", data);
    }

    function sendBaseViewData(data) {
        fetchData("/base-view-api", data);
    }

    function sendBaseUrlViewData(baseUrl) {
        const viewDataKey = `${baseUrl}_viewDataSent`;

        if (!sessionStorage.getItem(viewDataKey)) {
            const viewData = { slug: baseUrl, token: token };
            sendBaseViewData(viewData);
            sessionStorage.setItem(viewDataKey, "true");
        } else {
            console.log("View data already sent for this session.");
        }
    }

function setupClickListeners() {
    const currentUrl = window.location.host + window.location.pathname;
    const matchedData = dataMapping.find((data) => {
        const normalizedCurrentUrl = normalizeUrl(currentUrl);
        const normalizedSlug = normalizeUrl(data.slug);
        return (
            normalizedCurrentUrl === normalizedSlug ||
            normalizedCurrentUrl.startsWith(`${normalizedSlug}/`)
        );
    });

    if (!matchedData) {
        console.error("No matched data for current URL:", currentUrl);
        return;
    }

    console.log("Matched Data:", matchedData);

    const { selector, variant, slug } = matchedData;
    const elements = document.querySelectorAll(selector);
    const sessionKey = `${slug}_${selector}_submitted`;

    if (sessionKeys.has(sessionKey)) return; // Prevent duplicate listeners
    sessionKeys.add(sessionKey);

    let isSubmitting = false;

    const sendData = () => {
        if (isSubmitting || sessionStorage.getItem(sessionKey)) return;
        isSubmitting = true;

        const clickData = {
            url: normalizeUrl(window.location.href),
            selector,
            variant,
            token,
        };
        sendClickData(clickData);
        sessionStorage.setItem(sessionKey, "true");
        console.log("Data sent to API:", clickData);

        setTimeout(() => (isSubmitting = false), 500); // Throttle
    };

    elements.forEach((element) => {
        if (element.tagName === "FORM") {
            if (element.classList.contains("wpcf7-form")) {
                document.addEventListener("wpcf7mailsent", (event) => {
                    if (event.target.matches(selector) && !sessionStorage.getItem(sessionKey)) {
                        sendData();
                    }
                });
            } else {
                element.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    if (!sessionStorage.getItem(sessionKey)) {
                        try {
                            const formData = new FormData(element);
                            const response = await fetch(element.action, {
                                method: "POST",
                                body: formData,
                            });
                            if (response.ok) {
                                sendData();
                            } else {
                                console.error("Form submission failed.");
                            }
                        } catch (error) {
                            console.error("Error submitting form:", error);
                        }
                    }
                });
            }
        } else {
            element.addEventListener("click", () => {
                if (!sessionStorage.getItem(sessionKey)) {
                    sendData();
                } else {
                    console.log("Element already clicked in this session.");
                }
            });
        }
    });
}

    function checkCurrentUrl() {
        const normalizedUrl = normalizeUrl(window.location.href);
        const matchedData = dataMapping.find((data) =>
            normalizedUrl.includes(normalizeUrl(data.slug))
        );

        if (matchedData) {
            const viewDataKey = `${matchedData.slug}_viewDataSent`;
            if (!sessionStorage.getItem(viewDataKey)) {
                const viewData = {
                    slug: matchedData.slug,
                    token: token,
                };
                sendViewData(viewData);
                sessionStorage.setItem(viewDataKey, "true");
            } else {
                console.log("View data already sent for this session.");
            }
        } else {
            console.log("No matching URL found.");
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        fetch(`${apiBaseUrl}/get-domain/${token}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        })
            .then((response) => response.json())
            .then((apiData) => {
                const domainName = normalizeUrl(apiData.domain_name);
                const currentUrl = normalizeUrl(window.location.href);

			console.log(domainName)
			console.log(currentUrl)
                if (currentUrl.includes(domainName)) {
                    sendBaseUrlViewData(domainName);

                    const selectedData =
                        dataMapping[Math.floor(Math.random() * dataMapping.length)];

                    let tempSlugHits = localStorage.getItem(`${selectedData.slug}_hits`) || 0;
                    tempSlugHits++;
                    localStorage.setItem(`${selectedData.slug}_hits`, tempSlugHits);

                    sessionStorage.setItem("hasRedirected", "true");

                    document.body.style.visibility = "hidden";
                    setTimeout(() => {
                        document.body.innerHTML =
                            '<div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #fff;"><img src="https://i.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" /></div>';
                        document.body.style.visibility = "visible";

                        setTimeout(() => {
                            window.location.href = `https://${selectedData.slug}`;
                        }, 500);
                    }, 1000);
                }
            })
            .catch((error) => console.error("Error fetching domain:", error));

        if (sessionStorage.getItem("hasRedirected")) {
            checkCurrentUrl();
            setupClickListeners();
        }
    });
})();