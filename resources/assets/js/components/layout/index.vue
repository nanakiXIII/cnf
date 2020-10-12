<template>
    <div>
        <set-component v-if="$route.name == 'set' || $route.name == 'seedteam_projets'"></set-component>
        <layout-component v-else></layout-component>
    </div>


</template>