<script setup lang="ts">
    import IpDisplay from "$components/IpDisplay.vue";

    defineProps<{
        ip: string;
    }>();
</script>

<template>
	<IpDisplay :ip="ip" />
</template>
