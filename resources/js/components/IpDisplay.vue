<script setup lang="ts">
    defineProps<{
        ip: string;
    }>();
</script>

<template>
	<div class="ip-box">
		<h1>Your IP</h1>
		<p>{{ ip }}</p>
	</div>
</template>

<style lang="scss" scoped>
    @use '$styles/colors' as *;
    .ip-box {
        display: flex;
        flex-direction: column;
        gap: 12px;
        color: $primary-color;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: fit-content;
        margin: 40px auto;
        font-family: sans-serif;
    }
</style>
