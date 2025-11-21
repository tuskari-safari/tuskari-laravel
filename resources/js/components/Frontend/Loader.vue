<template>
  <div class="spinner-overlay">
    <ProgressSpinner class="custom-spinner" animationDuration="1.6s" strokeWidth="4" style="width: 80px; height: 50px" />
  </div>
</template>

<style scoped>


/* 🎨 Custom spinner color */
::v-deep(.custom-spinner svg circle) {
  stroke: #485648 !important; /* Your custom color here */
}

</style>
