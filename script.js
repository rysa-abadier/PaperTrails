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