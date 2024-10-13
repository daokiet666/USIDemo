<style>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    font-family: Roboto, sans-serif;
}

.pagination-button {
    padding: 5px 10px;
    margin: 0 5px;
    border: 1px solid #ccc;
    cursor: pointer;
}

.pagination-button:hover {
    background-color: #f0f0f0;
}

.active {
    background-color: #007bff;
    color: #fff;
}
</style>
</head>

<body>

    <div id="pagination" class="pagination"></div>

    <script>
    function generatePagination(totalPages, currentPage) {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        const currentURL = new URL(window.location.href);
        currentURL.searchParams.set('page', currentPage); // Set 'page' parameter to current page
        const baseURL = currentURL.toString(); // Convert URL object back to string

        // Add previous button
        const previousButton = document.createElement('span');
        previousButton.className = 'pagination-button';
        previousButton.innerText = '❮';
        previousButton.onclick = () => {
            if (currentPage > 1) {
                currentURL.searchParams.set('page', currentPage - 1);
                window.location.href = currentURL.toString();
            }
        };
        paginationContainer.appendChild(previousButton);

        // Add page numbers
        const numToShow = 5;
        let start = Math.max(1, currentPage - Math.floor(numToShow / 2));
        let end = Math.min(totalPages, start + numToShow - 1);
        if (end - start + 1 < numToShow) {
            start = Math.max(1, end - numToShow + 1);
        }

        if (start > 1) {
            const firstPage = document.createElement('span');
            firstPage.className = 'pagination-button';
            firstPage.innerText = '1';
            firstPage.onclick = () => {
                currentURL.searchParams.set('page', 1);
                window.location.href = currentURL.toString();
            };
            paginationContainer.appendChild(firstPage);
            if (start > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.innerText = '...';
                paginationContainer.appendChild(ellipsis);
            }
        }

        for (let i = start; i <= end; i++) {
            const pageButton = document.createElement('span');
            pageButton.className = 'pagination-button' + (i === currentPage ? ' active' : '');
            pageButton.innerText = i;
            pageButton.onclick = () => {
                currentURL.searchParams.set('page', i);
                window.location.href = currentURL.toString();
            };
            paginationContainer.appendChild(pageButton);
        }

        if (end < totalPages) {
            if (end < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.innerText = '...';
                paginationContainer.appendChild(ellipsis);
            }
            const lastPage = document.createElement('span');
            lastPage.className = 'pagination-button';
            lastPage.innerText = totalPages;
            lastPage.onclick = () => {
                currentURL.searchParams.set('page', totalPages);
                window.location.href = currentURL.toString();
            };
            paginationContainer.appendChild(lastPage);
        }

        // Add next button
        const nextButton = document.createElement('span');
        nextButton.className = 'pagination-button';
        nextButton.innerText = '❯';
        nextButton.onclick = () => {
            if (currentPage < totalPages) {
                currentURL.searchParams.set('page', currentPage + 1);
                window.location.href = currentURL.toString();
            }
        };
        paginationContainer.appendChild(nextButton);
    }

    // Get current page from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = parseInt(urlParams.get('page')) || 1;
    <?php if (isset($totalPages)): ?>
    var totalPages = <?php echo $totalPages; ?>;
    <?php else: ?>
    var totalPages = 10; // Giá trị mặc định nếu $totalPage không tồn tại
    <?php endif; ?>
    // Example usage with total pages
    generatePagination(totalPages, currentPage);
    </script>