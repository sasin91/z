import {createVuetify} from "vuetify";
// import colors from 'vuetify/lib/util/colors'
import 'vuetify/dist/vuetify.min.css'

export default createVuetify({
    theme: {
        defaultTheme: "light",

        themes: {
            light: {
                dark: false,
                colors: {
                    background: "#FFFFFF",
                    surface: "#FFFFFF",
                    primary: "#A60402",
                    secondary: "#FFCDD2",
                    error: "#CC0000",
                    info: "#0099CC",
                    success: "#007E33",
                    warning: "#FF8800",
                },
                variables: {
                    //
                }
            }
        },
    }
});
