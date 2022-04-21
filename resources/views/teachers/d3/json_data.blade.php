<script>

    var total_act = @jsvar($total_act);
    var total_sub = @jsvar($total_sub);

    var badges = @jsvar($gld);
    var rrs    = @jsvar($gldrr);

    var beng = badges.gamification_engines;
    var brrs = rrs.gamification_engines;
    var data   = [];

    console.log(beng);

    var iix=0;

    /*
     * First group of badges
     */
    for(iix=0; iix<4; iix++){
        var json = {"categorie": beng[iix].name,
                "values": [{"value" : total_sub["3"]['r'+(iix+1)],"rate"  : "Act Ctrl"},
                           {"value" : total_sub["1"]['r'+(iix+1)],"rate"  : "Act Bdgs"},
                           {"value" : total_sub["2"]['r'+(iix+1)],"rate"  : "Act RRs"},
                           {"value" : total_act["3"]['r'+(iix+1)],"rate"  : "Group Ctrl"},
                           {"value" : total_act["1"]['r'+(iix+1)],"rate"  : "Group Bdgs"},
                           {"value" : total_act["2"]['r'+(iix+1)],"rate"  : "Group RRs"},

                           {"value" : beng[iix].rewarded_students.length,"rate"  : "Rw Bdgs"},
                           {"value" : brrs[iix].rewarded_students.length,"rate"  : "Rw RRs"}
                          ]};
        data.push(json);
    }

    /*
     * Week 4
     */
    var json = {"categorie": "Module 3",
                "values": [ {"value" : total_sub["3"]['r9'],"rate"  : "Act Ctrl"},
                            {"value" : total_sub["1"]['r9'],"rate"  : "Act Bdgs"},
                            {"value" : total_sub["2"]['r9'],"rate"  : "Act RRs"}
                          ]};
    data.push(json);

    /*
     * Second group of badges
     */
    for(iix=5; iix<beng.length; iix++){
        var json = {"categorie": beng[iix].name,
            "values": [{"value" : total_sub["3"]['r'+(iix+1)],"rate"  : "Act Ctrl"},
                {"value" : total_sub["1"]['r'+(iix+1)],"rate"  : "Act Bdgs"},
                {"value" : total_sub["2"]['r'+(iix+1)],"rate"  : "Act RRs"},
                {"value" : total_act["3"]['r'+(iix+1)],"rate"  : "Group Ctrl"},
                {"value" : total_act["1"]['r'+(iix+1)],"rate"  : "Group Bdgs"},
                {"value" : total_act["2"]['r'+(iix+1)],"rate"  : "Group RRs"},

                {"value" : beng[iix].rewarded_students.length,"rate"  : "Rw Bdgs"},
                {"value" : brrs[iix].rewarded_students.length,"rate"  : "Rw RRs"}
            ]};
        data.push(json);
    }

    /*
     * Reward 5 wanted to be displayed at the end
     */
    iix = 4;
    var json = {"categorie": beng[iix].name,
        "values": [{"value" : total_sub["3"]['r'+(iix+1)],"rate"  : "Act Ctrl"},
            {"value" : total_sub["1"]['r'+(iix+1)],"rate"  : "Act Bdgs"},
            {"value" : total_sub["2"]['r'+(iix+1)],"rate"  : "Act RRs"},
            {"value" : total_act["3"]['r'+(iix+1)],"rate"  : "Group Ctrl"},
            {"value" : total_act["1"]['r'+(iix+1)],"rate"  : "Group Bdgs"},
            {"value" : total_act["2"]['r'+(iix+1)],"rate"  : "Group RRs"},

            {"value" : beng[iix].rewarded_students.length,"rate"  : "Rw Bdgs"},
            {"value" : brrs[iix].rewarded_students.length,"rate"  : "Rw RRs"}
        ]};
    data.push(json);


    /*
     * Week 7
     */
    var json = {"categorie": "Module 6",
        "values": [{"value" : total_sub["3"]['r10'],"rate"  : "Act Ctrl"},
            {"value" : total_sub["1"]['r10'],"rate"  : "Act Bdgs"},
            {"value" : total_sub["2"]['r10'],"rate"  : "Act RRs"}
        ]};
    data.push(json);

    console.log(data);

/*
    var data = [
        {
            "categorie": "Student",
            "values": [
                {
                    "value": 0,
                    "rate": "Not at all"
                },
                {
                    "value": 4,
                    "rate": "Not very much"
                },
                {
                    "value": 12,
                    "rate": "Medium"
                },
                {
                    "value": 6,
                    "rate": "Very much"
                },
                {
                    "value": 0,
                    "rate": "Tremendously"
                }
            ]
        },
        {
            "categorie": "Liberal Profession",
            "values": [
                {
                    "value": 1,
                    "rate": "Not at all"
                },
                {
                    "value": 21,
                    "rate": "Not very much"
                },
                {
                    "value": 13,
                    "rate": "Medium"
                },
                {
                    "value": 18,
                    "rate": "Very much"
                },
                {
                    "value": 6,
                    "rate": "Tremendously"
                }
            ]
        },
        {
            "categorie": "Salaried Staff",
            "values": [
                {
                    "value": 3,
                    "rate": "Not at all"
                },
                {
                    "value": 22,
                    "rate": "Not very much"
                },
                {
                    "value": 6,
                    "rate": "Medium"
                },
                {
                    "value": 15,
                    "rate": "Very much"
                },
                {
                    "value": 3,
                    "rate": "Tremendously"
                }
            ]
        },
        {
            "categorie": "Employee",
            "values": [
                {
                    "value": 12,
                    "rate": "Not at all"
                },
                {
                    "value": 7,
                    "rate": "Not very much"
                },
                {
                    "value": 18,
                    "rate": "Medium"
                },
                {
                    "value": 13,
                    "rate": "Very much"
                },
                {
                    "value": 6,
                    "rate": "Tremendously"
                }
            ]
        },
        {
            "categorie": "Craftsman",
            "values": [
                {
                    "value": 6,
                    "rate": "Not at all"
                },
                {
                    "value": 15,
                    "rate": "Not very much"
                },
                {
                    "value": 9,
                    "rate": "Medium"
                },
                {
                    "value": 12,
                    "rate": "Very much"
                },
                {
                    "value": 3,
                    "rate": "Tremendously"
                }
            ]
        },
        {
            "categorie": "Inactive",
            "values": [
                {
                    "value": 6,
                    "rate": "Not at all"
                },
                {
                    "value": 6,
                    "rate": "Not very much"
                },
                {
                    "value": 6,
                    "rate": "Medium"
                },
                {
                    "value": 2,
                    "rate": "Very much"
                },
                {
                    "value": 3,
                    "rate": "Tremendously"
                }
            ]
        }
    ];
*/

</script>
