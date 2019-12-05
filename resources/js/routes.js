export default [
    { path: '/', redirect: '/commands' },

    {
        path: '/jobs/:id',
        name: 'job-preview',
        component: require('./screens/jobs/preview').default,
    },

    {
        path: '/jobs',
        name: 'jobs',
        component: require('./screens/jobs/index').default,
    },

    {
        path: '/commands/:id',
        name: 'command-preview',
        component: require('./screens/commands/preview').default,
    },

    {
        path: '/commands',
        name: 'commands',
        component: require('./screens/commands/index').default,
    },

    {
        path: '/schedule/:id',
        name: 'schedule-preview',
        component: require('./screens/schedule/preview').default,
    },

    {
        path: '/schedule',
        name: 'schedule',
        component: require('./screens/schedule/index').default,
    },
];
