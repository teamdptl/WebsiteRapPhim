function sortMovie(columnIndex, sortType = 2){
    let table = document.querySelector("#tablePhim");
    sortTable(table, columnIndex, sortType);
}

function sortRap(columnIndex, sortType = 2){
    let table = document.querySelector("#tableRap");
    sortTable(table, columnIndex, sortType);
}

function sortTable(table, columnIndex, sortType = 2) {
    let rows = Array.from(table.getElementsByTagName("tr"));
    let header = rows.shift(); // Remove the table header row from the array
    // Determine the sorting order (ascending or descending) based on the current column state
    let sortAscending = true;
    if (header.children[columnIndex].dataset.sort === "asc") {
        sortAscending = false;
    }
    // Sort the rows based on the column values
    rows.sort(function(a, b) {
        let aValue = a.children[columnIndex].innerText;
        let bValue = b.children[columnIndex].innerText;
        if (sortType === 1){
            if (sortAscending) {
                return parseInt(bValue) - parseInt(aValue);
            } else {
                return parseInt(aValue) - parseInt(bValue);
            }
        }
        else {
            if (sortAscending) {
                return aValue.localeCompare(bValue);
            } else {
                return bValue.localeCompare(aValue);
            }
        }
    });


    // Update the table with the sorted rows
    let tbody = table.getElementsByTagName("tbody")[0];
    tbody.innerHTML = "";
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });

    // Update the sort indicator in the header
    header.children[columnIndex].dataset.sort = sortAscending ? "asc" : "desc";
}