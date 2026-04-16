<template>
  <p>Welcome, {{ user?.name }}</p>
</template>
<script setup>
const user = useSanctumUser()
</script>