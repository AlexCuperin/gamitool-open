<style>

    .axis path,
    .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
    }

    /*
    .x.axis path {
        display: none;
    }
    */

    .x-scroll{
        overflow-x: auto;
    }

    ::-webkit-scrollbar {
        width: 12px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
    }
</style>

<script src="https://d3js.org/d3.v3.min.js"></script>

<div class="hide row mb-r {{$targetclass}}" id="{{$targetid}}{{$reportid}}">
    <div class="card x-scroll" id="comparisond3">

    </div>
</div>

@include('teachers.d3.json_data')

<script>
    var margin = {top: 20, right: 20, bottom: 30, left: 40},
        width = Math.min($('#reportbadges').width()*0.9,1150) - margin.left - margin.right,
        height = Math.min($(document).width()*0.4,500) - margin.top - margin.bottom;

    var x0 = d3.scale.ordinal()
        .rangeRoundBands([0, width], .1);

    var x1 = d3.scale.ordinal();

    var y = d3.scale.linear()
        .range([height, 0]);

    var xAxis = d3.svg.axis()
        .scale(x0)
        .tickSize(0)
        .orient("bottom");

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var color = d3.scale.ordinal()
        .range([
                "#FFFFFF",          // Act control
                "#FFFFFF",          // Act badges
                "#FFFFFF",           // Act RRs
                "#FFDB89",          // Control
                "#92c5de",          // Total badges
                "#D4B2D8",          // Total RRs
                "#0571b0",          // Rewarded badges
                "#7553BA"           // Rewarded RRs
        ]);

    var svg = d3.select('#comparisond3').append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height+100 + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");



    var categoriesNames = data.map(function(d) { return d.categorie; });
    var rateNames = data[0].values.map(function(d) { return d.rate; });

    x0.domain(categoriesNames);
    x1.domain(rateNames).rangeRoundBands([0, x0.rangeBand()]);
    y.domain([0, d3.max(data, function(categorie) { return d3.max(categorie.values, function(d) { return d.value; }); })]);

    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + (height) + ")")
        .call(xAxis)
       .selectAll("text")
        .attr("dx", "-30")
        .attr("dy", "50")
        .attr("transform", "rotate(-45)");

    svg.append("g")
        .attr("class", "y axis")
        .style('opacity','0')
        .call(yAxis)
        .append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .style('font-weight','bold')
        .text("#students that completed gamification conditions");

    svg.select('.y').transition().duration(500).delay(1300).style('opacity','1');

    var slice = svg.selectAll(".slice")
        .data(data)
        .enter().append("g")
        .attr("class", "g")
        .attr("transform",function(d) { return "translate(" + x0(d.categorie) + ",0)"; });

    slice.selectAll("rect")
        .data(function(d) { return d.values; })
        .enter().append("rect")
        .attr("width", x1.rangeBand())
        .attr("x", function(d) {
            var pos;
            switch(d.rate){
                case 'Group Ctrl': pos = x1(d.rate)- 3*x1.rangeBand(); break;
                case 'Group Bdgs': pos = x1(d.rate)- 3*x1.rangeBand(); break;
                case 'Group RRs':  pos = x1(d.rate)- 3*x1.rangeBand(); break;
                case 'Rw Bdgs':    pos = x1(d.rate)- 5*x1.rangeBand(); break;
                case 'Rw RRs':     pos = x1(d.rate)- 5*x1.rangeBand(); break;
                default: pos = x1(d.rate);
            }

            //console.log(d.rate + ' ' + x1(d.rate) + ' ' + x1.rangeBand()/2 +' ' + pos);
            return pos+2*x1.rangeBand();
        })
        .attr('ix', function(d) {
            return d3.select(this).attr('x') -
                 (-d3.transform(d3.select(this.parentNode).attr("transform")).translate[0]) -
                 (-25);
        })
        .attr('class', function (d) {
            var idx = d3.select(this).attr('ix');
            var rclass= '';
            switch(d.rate){
                case 'Group Ctrl': rclass = 'r'+idx+' gctrl    relem'; break;
                case 'Group Bdgs': rclass = 'r'+idx+' gbadges  relem'; break;
                case 'Group RRs':  rclass = 'r'+idx+' grrs     relem'; break;
                case 'Rw Bdgs':    rclass = 'r'+idx+' rbadges  relem'; break;
                case 'Rw RRs':     rclass = 'r'+idx+' rrrs     relem'; break;
                case 'Act Ctrl':   rclass = 'r'+idx+' acontrol relem'; break;
                case 'Act Bdgs':   rclass = 'r'+idx+' abadges  relem'; break;
                case 'Act RRs':    rclass = 'r'+idx+' arrs     relem'; break;
            }
            return rclass;
        })
        .style("fill", function(d) { return color(d.rate) })
        .attr('stroke', '#000')
        .attr('stroke-width', '2')
        .attr("y", function(d) { return y(0); })
        .attr("height", function(d) { return height - y(0); })
        .on("mouseover", function(d) {
            var idx = d3.select(this).attr('ix');
            switch (d.rate){
                case 'Act Ctrl':   d3.selectAll('.r'+idx+'.gctrl'  ).attr('display', "none"); break;
                case 'Act Bdgs':   d3.selectAll('.r'+idx+'.gbadges').attr('display', "none");
                case 'Group Bdgs': d3.selectAll('.r'+idx+'.rbadges').attr('display', "none"); break;
                case 'Act RRs':    d3.selectAll('.r'+idx+'.grrs'   ).attr('display', "none");
                case 'Group RRs':  d3.selectAll('.r'+idx+'.rrrs'   ).attr('display', "none"); break;
            }
            d3.select(this).style("fill", d3.rgb(color(d.rate)).darker(2));
            tooltip.style("display", null);
        })
        .on("mouseout", function(d) {
            d3.select(this).style("fill", color(d.rate));
            tooltip.style("display", "none");
            d3.selectAll('.relem').attr('display','flex');
        })
        .on("mousemove", function(d) {
            var xPosition = d3.select(this).attr('ix');
            var yPosition = d3.mouse(this)[1] - 5;

            tooltip.attr("transform", "translate(" + xPosition + "," + yPosition + ")");
            tooltip.select("text").text(d.value);
        });

    slice.selectAll("rect")
        .transition()
        .delay(function (d) {return Math.random()*1000;})
        .duration(1000)
        .attr("y", function(d) { return y(d.value); })
        .attr("height", function(d) { return height - y(d.value); });

    //Legend
    var legend = svg.selectAll(".legend")
        .data(data[0].values.map(function(d) { return d.rate; }))
        .enter().append("g")
        .attr("class", "legend")
        .attr("transform", function(d,i) {
            if(d == 'Rw Bdgs' || d == 'Rw RRs')
                return "translate(0," + ((i * 25)+40) + ")";
            else if(d == 'Group Ctrl' || d == 'Group Bdgs' || d == 'Group RRs')
                return "translate(0," + ((i * 25)+20) + ")";
            else
                return "translate(0," + i * 25 + ")";
        })
        .style("opacity","0");

    legend.append("rect")
        .attr("x", width - 18)
        .attr("width", 18)
        .attr("height", 18)
        .style("fill", function(d) { return color(d); })
        .attr('stroke', '#000')
        .attr('stroke-width', '2');

    legend.append("text")
        .attr("x", width - 24)
        .attr("y", 9)
        .attr("dy", ".35em")
        .style("text-anchor", "end")
        .text(function(d) {return d; });

    legend.transition().duration(500).delay(function(d,i){ return 1300 + 100 * i; }).style("opacity","1");


    // Prep the tooltip bits, initial display is hidden
    var tooltip = svg.append("g")
        .style("display", "none");

    tooltip.append("rect")
        .attr("width", 60)
        .attr("height", 20)
        .attr("fill", "white")
        .style("opacity", 0.5);

    tooltip.append("text")
        .attr("x", 30)
        .attr("dy", "1.2em")
        .style("text-anchor", "middle")
        .attr("font-size", "12px")
        .attr("font-weight", "bold");
</script>