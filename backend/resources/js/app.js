import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'
import {debounce} from "alpinejs/src/utils/debounce";

Alpine.plugin(persist)

Alpine.store('darkMode', {
    on: Alpine.$persist(window.matchMedia('(prefers-color-scheme: dark)').matches).as('darkMode_on'),
    toggle() {
        this.on = !this.on
    }
});

Alpine.store('screen', {
    sm: window.matchMedia('(min-width: 640px)').matches,
    md: window.matchMedia('(min-width: 768px)').matches,
    lg: window.matchMedia('(min-width: 1024px)').matches,
    xl: window.matchMedia('(min-width: 1280px)').matches,
})

window.addEventListener('resize', debounce(
    () => {
        Alpine.store('screen').sm = window.matchMedia('(min-width: 640px)').matches
        Alpine.store('screen').md = window.matchMedia('(min-width: 768px)').matches
        Alpine.store('screen').lg = window.matchMedia('(min-width: 1024px)').matches
        Alpine.store('screen').xl = window.matchMedia('(min-width: 1280px)').matches
    }, 200)
)

window.Alpine = Alpine

Alpine.start()


