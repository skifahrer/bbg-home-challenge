import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["categorySelect", "productList", "productDetail", "paginationTop", "paginationBottom"];
    apiUrl = {
        productList: "/api/products",
        productDetail: "/api/products/",
        categoryList: "/api/categories?limit=1000"
    };

    connect() {
        // show grid in default
        this.showProductListSceen(true);

        this.loadCategories();
        this.loadProducts(null);
    }

    loadCategories() {
        this.renderCategoryPlaceholder();

        fetch(this.apiUrl.categoryList)
            .then(res => res.json())
            .then(data => this.renderCategoryOptions(data.data))
            .catch(err => console.error("error loading categories:", err));
    }

    loadProducts(categoryId = null, page = 1) {
        this.renderProductGridPlaceholder();

        let url = this.apiUrl.productList + `?page=${page}`;
        url = categoryId ? url + `&category=${categoryId}` : url;
        fetch(url)
            .then(res => res.json())
            .then(data => this.renderProductList(data))
            .catch(err => console.error("error loading products:", err));
    }

    // events

    showProductDetail(event) {
        this.showProductListSceen(false);
        this.renderProductDetailPlaceholder();

        const id = event.currentTarget.dataset.id;
        fetch(this.apiUrl.productDetail + `${id}`)
            .then(res => res.json())
            .then(product => {
                this.productDetailTarget.innerHTML = `
        <div>
          <div class="row g-0 overflow-hidden flex-md-row flex-md-shrink-1 mb-4 h-md-250 position-relative">
              <div class="col-auto m-1 d-none d-lg-block">
                <img src="${product.image_url}" alt="${product.name}" width="auto" height="250">
              </div>
              <div class="col p-4 d-flex flex-column position-static border rounded shadow-sm ">
                <div class="d-flex justify-content-end align-items-center mb-5">
                  <a href="#" class="icon-link gap-1 icon-link-hover stretched-link" data-action="click->catalog#goBack">return back to products</a>
                </div>
                <h3 class="mb-2">${product.name}</h3>
                <strong class="d-inline-block mb-4 text-primary-emphasis">${product.category.name}</strong>
                <h3 class="mb-1">${(product.price / 100).toFixed(2)} &euro;</h3>
              </div>
          </div>
    
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <h5>Description:</h5>
            <p class="card-text mb-auto">${product.description}</p>
          </div>
        </div>
      </div>`;
            });
    }

    goBack() {
        this.showProductListSceen(true);
    }

    filterByCategory() {
        const categoryId = this.categorySelectTarget.value;
        this.showProductListSceen(true);
        this.loadProducts(categoryId, 1);
    }

    showProductListPage(event) {
        const categoryId = this.categorySelectTarget.value;
        const page = event.currentTarget.dataset.id;
        this.loadProducts(categoryId, page);
    }

    // Rendering

    showProductListSceen(show = true) {
        this.productDetailTarget.hidden = show;
        this.productListTarget.hidden = !show;
        this.paginationTopTarget.hidden = !show;
        this.paginationBottomTarget.hidden = !show;
    }

    renderProductList(listData) {
        let products = listData.data;

        this.renderPagination(listData.currentPage, listData.totalPageCount);

        products = products.map(product => `
            <div class="col" data-action="click->catalog#showProductDetail" data-id="${product.id}"> 
                <div class="card shadow-sm"> 
                    <div class="card-img-top text-center p-3">
                        <img src="${product.image_url}" alt="${product.name}" width="auto" height="250">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">${product.description.substring(0, 128)}</p> 
                        <div class="d-flex justify-content-end align-items-center">
                            <h4 class="text-body-secondary">${(product.price / 100).toFixed(2)} &euro;</h4>
                        </div>
                    </div> 
                </div> 
            </div>
        `);

        this.productListTarget.innerHTML = `<div class="d-flex align-items-stretch row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 ">
            ${products.join("")}
        </div>`;
    }

    renderProductGridPlaceholder() {
        this.renderPaginationPlaceholder();

        let grid = [];
        for (let i = 0; i < 9; i++) {
            grid.push(`
            <div class="col"> 
                <div class="card shadow-sm"> 
                    <svg aria-label="Placeholder:" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                    </svg> 
                    <div class="card-body">
                        <h5 class="card-title">
                            <span class="placeholder col-4"></span>
                        </h5>
                        <p class="card-text">
                            <span class="placeholder col-7"></span>
                        </p>
                        <div class="d-flex justify-content-end align-items-center">
                            <h4 class="text-body-secondary">
                                <span class="placeholder col-4"></span>
                            </h4>
                        </div>
                    </div> 
                </div> 
            </div>
            `);
        }
        this.productListTarget.innerHTML = `
            <div class="d-flex align-items-stretch row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 ">
            ${grid.join("")}
            </div>
        `;
    }

    renderProductDetailPlaceholder() {
        this.productDetailTarget.innerHTML = `
        <div>
          <div class="row g-0 overflow-hidden flex-md-row flex-md-shrink-1 mb-4 h-md-250 position-relative">
              <div class="col-auto m-1 d-none d-lg-block">
                <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img " height="250" preserveAspectRatio="xMidYMid slice" role="img" width="250" xmlns="http://www.w3.org/2000/svg">
                  <title>Placeholder</title>
                  <rect width="100%" height="100%" fill="#55595c"></rect>
                </svg>
              </div>
              <div class="col p-4 d-flex flex-column position-static border rounded shadow-sm ">
                <div class="d-flex justify-content-end align-items-center mb-5">
                  <a href="#" class="icon-link gap-1 icon-link-hover stretched-link" data-action="click->catalog#goBack">return back to products</a>
                </div>
                <h3 class="mb-2"><span class="placeholder col-4"></span></h3>
                <strong class="d-inline-block mb-4 text-primary-emphasis"><span class="placeholder col-4"></span></strong>
                <h3 class="mb-1"><span class="placeholder col-4"></span></h3>
              </div>
          </div>
    
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <h5>Description:</h5>
            <p class="card-text mb-auto"><span class="placeholder col-12"></span></p>
          </div>
        </div>
      </div>`;
    }

    renderCategoryPlaceholder() {
        this.categorySelectTarget.innerHTML = '' +
            ' <option selected value="">Show all</option>\n' +
            '          <option disabled>...Loading Categories</option>';
    }

    renderCategoryOptions(data) {
        this.categorySelectTarget.innerHTML = ' <option selected value="">Show all</option>\n' +
            data.map(category => `
                <option value="${category.id}">${category.name}</option>
    `       ).join("");
    }

    renderPagination(currentPage, totalPages) {
        const delta = 2;
        const start = Math.max(1, currentPage - delta);
        const end = Math.min(totalPages, currentPage + delta);

        let pages = [];

        for (let i = start; i <= end; i++) {
            let active = (i === currentPage ? ' active' : '');
            pages.push(`<a
                href="#"
                class="btn btn-primary${active}"
                data-action="click->catalog#showProductListPage"
                data-id="${i}"
               '>${i}</a>
            `);
        }

        // last button if more pages available
        if (totalPages > end) {
            pages.push(`<a
                href="#"
                class="btn btn-primary"
                data-action="click->catalog#showProductListPage"
                data-id="${totalPages}"
               '>${totalPages}</a>
            `);
        }

        this.paginationTopTarget.innerHTML = pages.join("");
        this.paginationBottomTarget.innerHTML = pages.join("");
    }

    renderPaginationPlaceholder() {
        const loading= `<a href="#" class="btn btn-primary disabled">... Loading</a>`;
        this.paginationTopTarget.innerHTML = loading;
        this.paginationBottomTarget.innerHTML = loading;
    }
}
