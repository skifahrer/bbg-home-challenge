<template>
  <div class="product-page">
    <h1>{{ product.name }}</h1>
    <img :src="product.image_url" alt="Product Image" />
    <p>{{ product.description }}</p>
    <p><strong>Price:</strong> {{ product.price }}</p>
    <p><strong>Category:</strong> {{ product.category.name }}</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ProductPage',
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      product: {}
    };
  },
  created() {
    axios.get(`http://localhost:8000/api/products/${this.id}`)
      .then(response => {
        this.product = response.data;
      })
      .catch(error => {
        console.error('Error fetching product:', error);
      });
  }
};
</script>

<style scoped>
.product-page {
  text-align: center;
  margin: 20px;
}
.product-page img {
  max-width: 100%;
  height: auto;
}
</style>