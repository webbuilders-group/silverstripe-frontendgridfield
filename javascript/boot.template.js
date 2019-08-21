(function() {
    var configDefault = {
        SecurityID: '$SecurityID',
        absoluteBaseUrl: '$AbsoluteBaseURL',
        baseUrl: '$BaseURL',
        adminUrl: 'admin/',
        environment: '$Environment',
        debugging: $Debugging,
        sections: [
            {
                name: 'SilverStripe\\Admin\\LeftAndMain',
                url: 'admin',
                graphql: {
                    cachedTypenames: false,
                },
            },
        ],
    };
    
    window.ss = window.ss || {};
    window.ss.config = window.ss.config || configDefault;
})();