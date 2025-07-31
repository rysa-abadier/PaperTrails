document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll("tr[data-id]");

    rows.forEach(row => {
        row.addEventListener("click", function () {
            const form = document.createElement("form");
            form.method = "GET";
            form.action = "edit.php";

            const idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "id";
            idInput.value = row.dataset.id;

            const pageInput = document.createElement("input");
            pageInput.type = "hidden";
            pageInput.name = "page";
            pageInput.value = globalThis.currentPage || "unknown";

            form.appendChild(idInput);
            form.appendChild(pageInput);

            document.body.appendChild(form);

            form.submit();
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const editForm = document.querySelector(".edit-row");
    const deleteForm = document.querySelector(".delete-row");
    const buyItem = document.querySelector(".buy-row");

    if (editForm) {
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                submitForm(globalThis.currentPage, editForm, "update.php");
            } else if (event.key === 'Escape') {
                event.preventDefault();
                submitForm("cancel", editForm, "update.php");
            } else if (event.key === 'Delete') {
                event.preventDefault();
                submitForm(globalThis.currentPage, deleteForm, "delete.php");
            }
        });
    }

    function submitForm(pageName, form, url) {
        setActionForm(form, url);

        const hiddenButton = document.createElement("button");
        hiddenButton.type = "submit";
        hiddenButton.name = pageName;
        hiddenButton.style.display = "none";
        form.appendChild(hiddenButton);
        hiddenButton.click();
    }

    function setActionForm(form, url) {
        return form.setAttribute("action", url);
    }
});