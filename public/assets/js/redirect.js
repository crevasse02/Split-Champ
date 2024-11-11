const redirectScript = `
document.addEventListener("DOMContentLoaded",function(){if(!function t(){let e=window.location.origin,n="baseUrl_viewDataSent";if(e&&"string"==typeof e&&(e=(e=e.replace("http://","").replace("https://","")).replace("www.","")),sessionStorage.getItem(n))console.log("View data already sent for the base URL in this session.");else{let i={slug:e,token:token};(function t(e){fetch("https://split.esensigroup.com/base-view-api",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(e)}).then(t=>t.json()).then(t=>console.log("Success:",t)).catch(t=>console.error("Error:",t))})(i),sessionStorage.setItem(n,"true")}}(),!function t(){let e=window.location.host+window.location.pathname,n=dataMapping.find(t=>e.includes(t.slug));if(n){let i=n.slug+"_viewDataSent";if(sessionStorage.getItem(i))console.log("View data already sent for this user in this session.");else{let s={slug:n.slug,token:token};(function t(e){fetch("https://split.esensigroup.com/view-api",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(e)}).then(t=>t.json()).then(t=>console.log("Success:",t)).catch(t=>console.error("Error:",t))})(s),sessionStorage.setItem(i,"true")}}else console.log("No matching URL found.")}(),dataMapping.forEach(function(t){let e=t.selector,n=t.variant,i=t.slug,s=document.querySelectorAll(e);s.forEach(function(t){let s=!1,o=function(){let t=window.location.host+window.location.pathname;if(-1!==t.indexOf(i)){let s={url:t,selector:e,variant:n,token:token};(function t(e){fetch("https://split.esensigroup.com/tracker-api",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(e)}).then(t=>t.json()).then(t=>console.log("Success:",t)).catch(t=>console.error("Error:",t))})(s),console.log("Data sent to API:",s)}else console.log("Current URL does not match slug:",i)};"FORM"===t.tagName?t.classList.contains("wpcf7-form")?document.addEventListener("wpcf7mailsent",function(t){if(t.target.matches(e)&&!s){s=!0;let n=i+"_formSubmitted";sessionStorage.getItem(n)?console.log("Form already submitted in this session."):(o(),sessionStorage.setItem(n,"true"),console.log("Contact Form 7 sent successfully, data sent."))}}):t.addEventListener("submit",async function(e){if(e.preventDefault(),!s){s=!0;let n=i+"_formSubmitted";if(sessionStorage.getItem(n))console.log("Form already submitted in this session.");else try{let l=new FormData(t),a=await fetch(t.action,{method:"POST",body:l});a.ok?(o(),sessionStorage.setItem(n,"true"),console.log("Non-Contact Form 7 form submitted successfully, data sent.")):console.log("Form submission failed, data not sent.")}catch(r){console.error("Error submitting form:",r)}finally{s=!1}}}):t.addEventListener("click",function(){let t=i+"_clickTriggered";sessionStorage.getItem(t)?console.log("Click action already triggered in this session."):(o(),sessionStorage.setItem(t,"true"),console.log("Non-form element clicked, data sent."))})})}),sessionStorage.getItem("hasRedirected"))return;let t=dataMapping[Math.floor(Math.random()*dataMapping.length)],e=localStorage.getItem(t.slug+"_hits")?parseInt(localStorage.getItem(t.slug+"_hits")):0;e++,localStorage.setItem(t.slug+"_hits",e),sessionStorage.setItem("hasRedirected","true"),document.body.style.visibility="hidden",setTimeout(()=>{document.body.innerHTML='<div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #fff;"><img src="https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExajFnazV4bnE2M3NzY2hxeDhzd3J2ODdxamE2dmNvdTU1bGZvNm9tNyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3oEjI6SIIHBdRxXI40/giphy.gif" /></div>',document.body.style.visibility="visible",setTimeout(()=>{window.location.href=t.slug},500)},1e3)});
`;
