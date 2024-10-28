// For store experimen data
var btnSubmit = document.getElementById("create-experimen");
btnSubmit.addEventListener("click", function () {
    var experimenName = document.getElementById("eksperimen-name").value;
    var domainName = document.getElementById("domain-name").value;
    var createdBy = document.getElementById("created-by").value;
    var timeStamp = document.getElementById("time-stamp").value;

    var data = {
        experimenName: experimenName,
        domainName: domainName,
        createdBy: createdBy,
        timeStamp: timeStamp,
    };
});

//For store
