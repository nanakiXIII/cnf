<template>
    <section class="light">
        <main>
            <navbar-component></navbar-component>
            <router-view></router-view>
        </main>
        <footer>
            <div class="bg-grey p-5 mt-5 footer">
                <div class="container footer-icon">
                    <div class="col-auto text-center">
                        <i class="fab fa-discord p-1"></i>
                        <i class="fab fa-twitter p-1"></i>
                        <i class="fas fa-rss p-1"></i>
                        <i class="fas fa-envelope p-1"></i>
                    </div>
                </div>
                <div class="col-auto text-center p-1 footer-text">
                    Accueil | Equipes | Nous contacter | Partenaires
                    <br>
                    <small>© 2018 Chuushin no Fansub</small>
                </div>
            </div>
        </footer>
    </section>

</template>

<script>
    export default {
        data(){
            return {
            }
        }
    }
</script>
