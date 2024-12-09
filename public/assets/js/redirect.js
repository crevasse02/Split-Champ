const redirectScript = `
(function(){function sendClickData(a){fetch("https://split.esensigroup.com/tracker-api",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(a)}).then(b=>b.json()).then(b=>console.log("Success:",b)).catch(b=>console.error("Error:",b))}function sendViewData(a){fetch("https://split.esensigroup.com/view-api",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(a)}).then(b=>b.json()).then(b=>console.log("Success:",b)).catch(b=>console.error("Error:",b))}function sendBaseViewData(a){fetch("https://split.esensigroup.com/base-view-api",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(a)}).then(b=>b.json()).then(b=>console.log("Success:",b)).catch(b=>console.error("Error:",b))}function sendBaseUrlViewData(a){let b=a;const c="baseUrl_viewDataSent";if(b&&"string"==typeof b){b=b.replace("http://","").replace("https://","");b=b.replace("www.","")}if(!sessionStorage.getItem(c)){const a={slug:b,token:token};sendBaseViewData(a);sessionStorage.setItem(c,"true")}else console.log("View data already sent for the base URL in this session.")}function checkCurrentUrl(){const a=window.location.host+window.location.pathname,b=dataMapping.find(b=>a.includes(b.slug));if(b){const c=b.slug+"_viewDataSent";if(!sessionStorage.getItem(c)){const a={slug:b.slug,token:token};sendViewData(a);sessionStorage.setItem(c,"true")}else console.log("View data already sent for this user in this session.")}else console.log("No matching URL found.")}function setupClickListeners(){dataMapping.forEach(({selector:a,variant:b,slug:c})=>{const d=document.querySelectorAll(a);d.forEach(d=>{let e=!1,f=!1;const g=()=>{const a=window.location.host+window.location.pathname;a.includes(c)&&(sendClickData({url:a,selector:a,variant:b,token:token}),console.log("Data sent to API:",{url:a,selector:a,variant:b,token:token}))};const h=c+"_"+a+"_submitted";if("FORM"===d.tagName){if(d.classList.contains("wpcf7-form")){document.addEventListener("wpcf7mailsent",a=>{a.target.matches(a)&&!e&&!sessionStorage.getItem(h)&&(e=!0,g(),sessionStorage.setItem(h,"true"),console.log("Contact Form 7 sent successfully, data sent."))});document.addEventListener("wpcf7invalid",a=>{a.target.matches(a)&&console.log("Contact Form 7 submission invalid, data not sent.")})}else d.addEventListener("submit",async a=>{a.preventDefault();if(!e&&!sessionStorage.getItem(h)){e=!0;try{const b=new FormData(d),c=await fetch(d.action,{method:"POST",body:b});c.ok?(g(),sessionStorage.setItem(h,"true"),console.log("Non-Contact Form 7 form submitted successfully, data sent.")):console.log("Form submission failed, data not sent.")}catch(a){console.error("Error submitting form:",a)}finally{e=!1}}})}else d.addEventListener("click",()=>{!f&&!sessionStorage.getItem(h)&&(f=!0,g(),sessionStorage.setItem(h,"true"),console.log("Non-form element clicked, data sent."))})})})}document.addEventListener("DOMContentLoaded",function(){if(sessionStorage.getItem("hasRedirected"))setupClickListeners();fetch("https://split.esensigroup.com/get-domain/"+token,{method:"GET",headers:{"Content-Type":"application/json"}}).then(a=>a.json()).then(a=>{const b=a.domain_name,c=window.location.host+window.location.pathname,d=c.replace("www.","").replace(/\/$/,""),e=b.replace("www.","").replace(/\/$/,"");d.includes(e)&&(sendBaseUrlViewData(b),sessionStorage.setItem("hasRedirected","true"),document.body.style.visibility="hidden",setTimeout(()=>{document.body.innerHTML='<div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #fff;"><img src="https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExajFnazV4bnE2M3NzY2hxeDhzd3J2ODdxamE2dmNvdTU1bGZvNm9tNyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3oEjI6SIIHBdRxXI40/giphy.gif" /></div>',document.body.style.visibility="visible",setTimeout(()=>{window.location.href="https://"+dataMapping[Math.floor(Math.random()*dataMapping.length)].slug},500)},1000))}).catch(a=>console.error("Error:",a))});if(sessionStorage.getItem("hasRedirected"))checkCurrentUrl()})();
`;
