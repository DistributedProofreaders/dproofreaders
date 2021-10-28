/* global d3 */
/* exported barLineGraph, stackedAreaGraph, pieGraph */

const {barLineGraph, stackedAreaGraph, pieGraph} = (function () {
    const margin = ({
        top: 30,
        right: 45,
        bottom: 30,
        left: 45
    });

    function addTitle(svg, config, width) {
        svg.append("text")
            .attr("x", (config.width || width) / 2)
            .attr("y", "13")
            .attr("font-size", "16px")
            .attr("text-anchor", "middle")
            .attr("fill", "currentColor")
            .text(config.title);
    }

    function addLegend(svg, config, series, width, height, reverseSeries = false) {
        const hasMultipleSeries = series.length > 1;
        const yAxisLabel = config.yAxisLabel || (!hasMultipleSeries ? Object.keys(config.data)[0] : null);
        if (hasMultipleSeries) {
            const container = svg.append("g");
            if (config.legendAdjustment) {
                container.attr("transform", `translate(${config.legendAdjustment.x},${config.legendAdjustment.y})`);
            }
            const legendBox = container.append("rect");
            container.selectAll("seriesColor")
                .data(series)
                .enter()
                .append("circle")
                .attr("cx", margin.left + (config.axisLeft ? 30 : 10))
                .attr("cy", (_,i) => (margin.left + 10) + ((reverseSeries ? (series.length - i - 1) : i) * 25))
                .attr("r", 7)
                .attr("class", (_, i) => config.barColors ? "" : `graph-series-fill-${i + 1}`);

            container.selectAll("series")
                .data(series)
                .enter()
                .append("text")
                .attr("fill", "currentColor")
                .attr("x", margin.left + (config.axisLeft ? 55 : 25))
                .attr("y", (_,i) => (margin.left + 10) + ((reverseSeries ? (series.length - i - 1) : i) * 25) + 5)
                .text(d => d);
            const containerBox = container.node().getBBox();
            legendBox.attr("width", containerBox.width + 6)
                .attr("height", containerBox.height + 6)
                .attr("x", containerBox.x - 3)
                .attr("y", containerBox.y - 3)
                .attr("class", "graph-legend");
        }

        if (yAxisLabel) {
            const series = svg.append("text")
                .attr("fill", "currentColor");
            series.attr("text-anchor", "middle");
            if (config.bottomLegend) {
                series.attr("x", (config.width || width) / 2)
                    .attr("y", height - 15)
                    .attr("font-size", "15px");
            } else {
                series.attr("transform", "rotate(-90)")
                    .attr("x", -((config.height || height) / 2))
                    .attr("y", 20);
            }
            series.text(yAxisLabel);
        }
    }

    function stackedAreaGraph(id, config) {
        const height = 400;
        const width = 640;
        const length = Object.entries(config.data)[0][1].x.length;
        const data = [];
        data.columns = Object.keys(config.data);
        for (let i = 0; i < length; i++) {
            const rowEntry = data.columns.reduce((previousValue, k, currentIndex) => {
                const v = config.data[k];
                previousValue[k] = v.y[i] - previousValue.currentY;
                previousValue.currentY = v.y[i];
                if (currentIndex === 0) {
                    previousValue.date = new Date(v.x[i]);
                }

                return previousValue;
            }, {currentY: 0});
            delete rowEntry.currentY;
            data.push(rowEntry);
        }
        data.columns = ["date", ...data.columns];
        data.y = "date";
        const series = d3.stack().keys(data.columns.slice(1))(data);

        const y = d3.scaleLinear()
            .domain([0, d3.max(series, d => d3.max(d, d => d[1]))])
            .nice()
            .range([height - margin.bottom, margin.top]);

        const yAxisTicks = y.ticks().filter(Number.isInteger);

        const x = d3.scaleUtc()
            .domain(d3.extent(data, d => d.date))
            .range([margin.left, width - margin.right]);

        const yAxis = g => g
            .attr("transform", `translate(${config.axisLeft ? margin.left : width - margin.right},0)`)
            .call((config.axisLeft ? d3.axisLeft(y) : d3.axisRight(y))
                .tickValues(yAxisTicks)
                .tickFormat(d3.format(",d")))
            .call(g => g.select(".domain").remove());
        const xAxis = g => g
            .attr("transform", `translate(0,${height - margin.bottom})`)
            .call(d3.axisBottom(x).ticks(width / 100)
                .tickFormat(d => `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`)
                .tickSizeOuter(0));

        const area = d3.area()
            .curve(d3.curveStep)
            .x(d => x(d.data.date))
            .y0(d => y(d[0]))
            .y1(d => y(d[1]));

        const svg = d3.select("#" + id).append("svg")
            .attr("viewBox", [0, 0, width, height]);

        svg.append("g")
            .selectAll("path")
            .data(series)
            .join("path")
            .attr("class", (_, i) => `graph-series-fill-${i + 1}`)
            .attr("d", area)
            .append("title")
            .text(({
                key
            }) => key);

        svg.append("g")
            .call(xAxis);

        svg.append("g")
            .call(yAxis);

        addTitle(svg, config, width);
        addLegend(svg, config, data.columns.slice(1) /* no date column */, width, height, true /* reverse */);
    }

    function barLineGraph(id, config) {
        const barMargin = {
            top: margin.top,
            right: margin.right,
            bottom: config.xAxisHeight || 50
        };
        const height = config.height || 400;
        const width = config.width || 640;
        const data = Object.entries(config.data).filter(([,{x}]) => x && x.length > 0);
        const svg = d3.select("#" + id).append("svg")
            .attr("viewBox", [0, 0, width, height]);
        if (data.length > 0) {
            const tooltip = d3.select("#" + id)
                .append("div")
                .attr("class", "graph-tooltip")
                .style("display", "none");

            const mouseAction = (event, {value}) => {
                tooltip.style("display", "")
                    .text(d3.format(',d')(value))
                    .style("left", (d3.pointer(event, document.body)[0]) + "px")
                    .style("top", Math.max(d3.pointer(event, document.body)[1] - 32, 0) + "px");
            };

            const mouseLeave = () => {
                tooltip.style("display", "none");
            };

            const yValues = data.map(([,{y}]) => y.map(Number))
                .reduce((previousValue, currentValue) => ([...previousValue, ...currentValue]), []);
            const minYValue = d3.min(yValues, d => d);
            const y = d3.scaleLinear()
                .domain([minYValue > 0 ? 0 : minYValue, d3.max(yValues, d => d)])
                .nice()
                .range([height - barMargin.bottom, barMargin.top]);

            let yAxisTicks = y.ticks();
            const yInterval = config.yAxisTickCount ? Math.ceil(yAxisTicks.length / config.yAxisTickCount) : 1;
            const integerYTicks = yAxisTicks.filter(Number.isInteger);
            if (integerYTicks.length > 1) {
                yAxisTicks = integerYTicks;
            }
            yAxisTicks = yAxisTicks.filter((_, i) => i % yInterval === 0);
            const yAxis = g => g
                .call(d3.axisLeft(y).tickValues(yAxisTicks))
                .call(g => g.select(".domain").remove());

            const yAxisGroup = svg.append("g")
                .call(yAxis);
            const left = yAxisGroup.node().getBBox().width + 25;
            yAxisGroup.attr("transform", `translate(${left},0)`);

            const xValues = data[0][1].x;
            const x = d3.scaleBand()
                .domain(d3.range(xValues.length))
                .range([left, width - barMargin.right])
                .padding(0.1);

            if (minYValue < 0) {
                svg.append("g")
                    .append("line")
                    .attr("x1", left)
                    .attr("x2", width - barMargin.right)
                    .attr("y1", y(0))
                    .attr("y2", y(0))
                    .attr("style", "stroke-width: .5px")
                    .attr("stroke", "currentColor");
            }

            let xGroupOffset;
            if (config.groupBars) {
                xGroupOffset = d3.scaleBand()
                    .domain(data.map(([title]) => title))
                    .rangeRound([0, x.bandwidth()])
                    .padding(0.05);
            } else {
                xGroupOffset = () => 0;
            }

            const interval = Math.ceil(xValues.length / 40);
            const xAxis = g => g
                .attr("transform", `translate(0,${height - barMargin.bottom})`)
                .call(d3.axisBottom(x)
                    .tickValues(x.domain().filter((_, i) => i % interval === 0))
                    .tickFormat(i => xValues[i])
                    .tickSizeOuter(0));

            const renderSeries = (seriesTitle, seriesData, seriesIndex, seriesToRender) => {
                const data = seriesData.x.reduce((acc, value, index) => {
                    acc.push({
                        [seriesTitle]: value,
                        value: Number(seriesData.y[index])
                    });
                    return acc;
                }, []);

                if (seriesToRender === "line" && seriesData.type === "line") {
                    const line = d3.line()
                        .x((d, i) => x(i))
                        .y(({value}) => value < 0 ? y(0) : y(value));

                    svg.append("path")
                        .datum(data)
                        .attr("fill", "none")
                        .attr("class", `graph-series-stroke-${seriesIndex + 1}`)
                        .attr("stroke-width", 2.5)
                        .attr("stroke-linejoin", "round")
                        .attr("stroke-linecap", "round")
                        .attr("d", line);
                } else if (seriesToRender === "bar" && (seriesData.type === undefined || seriesData.type === "bar")) {
                    svg.append("g")
                        .selectAll("rect")
                        .data(data)
                        .join("rect")
                        .attr("class", (_, i) => config.barColors ? config.barColors[i] : `graph-series-stroke-${seriesIndex + 1} graph-series-fill-${seriesIndex + 1}`)
                        .style("shape-rendering", "crispEdges")
                        .attr("stroke-width", 1)
                        .attr("x", (d, i) => x(i) + xGroupOffset(seriesTitle))
                        .attr("y", ({value}) => value < 0 ? y(0) : y(value))
                        .attr("height", d => Math.abs(y(0) - y(d.value)))
                        .attr("width", config.groupBars ? xGroupOffset.bandwidth() : Math.max(x.bandwidth(), 1))
                        .on("mouseover", mouseAction)
                        .on("mousemove", mouseAction)
                        .on("mouseleave", mouseLeave);
                }
            };

            data.forEach(([seriesTitle, seriesData], seriesIndex) => {
                renderSeries(seriesTitle, seriesData, seriesIndex, "bar" /* seriesToRender */);
            });

            svg.append("g")
                .call(xAxis)
                .selectAll("text")
                .attr("transform", "rotate(-90)")
                .attr("y", -5)
                .attr("x", -6)
                .style("text-anchor", "end");

            // render lines after xaxis so they are above axis, but bars are above.
            data.forEach(([seriesTitle, seriesData], seriesIndex) => {
                renderSeries(seriesTitle, seriesData, seriesIndex, "line" /* seriesToRender */);
            });
        }

        addTitle(svg, config, width);
        addLegend(svg, config, data.map(([title]) => title), width, height);
    }

    function pieGraph(id, config) {
        const pieMargin = {
            right: margin.right,
            left: margin.left,
            top: 50,
            bottom: 50
        };
        let total = 0;
        const data = config.data.reduce((acc, value, index) => {
            acc.push({name: config.labels[index], value});
            total += Number(value);
            return acc;
        }, []);
        const height = config.height || 400;
        const width = config.width || 660;

        const svg = d3.select("#" + id).append("svg")
            .attr("viewBox", [0, 0, width, height]);

        if (config.error) {
            svg.append("text").attr("class", "error")
                .attr("fill", "currentColor")
                .attr("x", pieMargin.left)
                .attr("y", pieMargin.top)
                .text(config.error);
        } else if(data.some(({value}) => value !== 0 && value !== null)) {
            const pie = d3.pie()
                .sort(null)
                .value(d => d.value);
            const radius = Math.min(width - (pieMargin.left + pieMargin.right), height - (pieMargin.top + pieMargin.bottom)) / 2;
            const arc = d3.arc()
                .innerRadius(0)
                .outerRadius(radius);

            const arcs = pie(data);

            const arcLabel = d3.arc().innerRadius(radius + 20)
                .outerRadius(radius + 25);

            svg.append("g")
                .attr("transform", `translate(${width / 2},${height / 2})`)
                .selectAll("path")
                .data(arcs)
                .join("path")
                .attr("class", (_, i) => `graph-series-fill-${i + 1}`)
                .attr("d", arc)
                .append("title")
                .text(d => `${d.data.name}`);

            svg.append("g")
                .attr("transform", `translate(${(width / 2) - 25},${(height / 2) + 12})`)
                .selectAll("text")
                .data(arcs)
                .join("text")
                .attr("transform", d => `translate(${arcLabel.centroid(d)})`)
                .call(text => text.attr("fill", "currentColor").append("tspan")
                    .attr("y", "-0.4em")
                    .attr("font-weight", "bold")
                    .text(d => Number(d.data.value) !== 0 ? `${d3.format(",.1f")((d.data.value / total) * 100)}%` : ''));
            addLegend(svg, config, config.labels, width, height);
        }

        addTitle(svg, config, width);
    }

    return {
        stackedAreaGraph,
        barLineGraph,
        pieGraph,
    };
})();
