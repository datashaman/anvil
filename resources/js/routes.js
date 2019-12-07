export default [
    { path: "/", redirect: "/commands" },

    {
        path: "/commands/:id",
        name: "command-preview",
        component: require("./screens/commands/preview").default
    },

    {
        path: "/commands",
        name: "commands",
        component: require("./screens/commands/index").default
    }
];
