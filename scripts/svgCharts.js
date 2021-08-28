/* global d3 */
/* exported barChart, stackedAreaChart, pieChart */

const {barChart, stackedAreaChart, pieChart} = (function () {
    const margin = ({
        top: 20,
        right: 45,
        bottom: 30,
        left: 30
    });

    function addTitle(svg, config, width, x, y) {
        svg.append("text")
            .attr("x", x !== undefined ? x : ((config.width || width) / 2))
            .attr("y", y !== undefined ? y : ((margin.top / 2) + 3))
            .attr("font-size", "16px")
            .attr("text-anchor", "middle")
            .attr("fill", "currentColor")
            .text(config.title);
    }

    function addLegend(svg, color, config, series, width, height, x, y) {
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
                .attr("cx", (x !== undefined ? x : margin.left) + (config.axisLeft ? 30 : 10))
                .attr("cy", (d,i) => (y !== undefined ? y : (margin.left + 10)) + i * 25)
                .attr("r", 7)
                .style("fill", d => color(d));

            container.selectAll("series")
                .data(series)
                .enter()
                .append("text")
                .attr("fill", "currentColor")
                .attr("x", (x !== undefined ? x : margin.left) + (config.axisLeft ? 55 : 25))
                .attr("y", (d,i) => ((y !== undefined ? y : (margin.left + 10)) + i * 25) + 5)
                .text(d => d);
            const containerBox = container.node().getBBox();
            legendBox.attr("width", containerBox.width + 6)
                .attr("height", containerBox.height + 6)
                .attr("x", containerBox.x - 3)
                .attr("y", containerBox.y - 3)
                .attr("class", "chart-legend");
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

    function stackedAreaChart(id, config) {
        const height = 400;
        const width = 640;
        const length = Object.entries(config.data)[0][1].x.length;
        const data = [];
        data.columns = Object.keys(config.data).sort((c1, c2) => config.data[c1].y[0] - config.data[c2].y[0]);
        for (let i = 0; i < length; i++) {
            const rowEntry = (data.columns.reduce((previousValue, k, currentIndex) => {
                const v = config.data[k];
                previousValue[k] = v.y[i] - previousValue.currentY;
                previousValue.currentY = v.y[i];
                if (currentIndex === 0) {
                    previousValue.date = new Date(v.x[i]);
                }

                return previousValue;
            }, {currentY: 0}));
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
                .tickFormat(d3.format("d")))
            .call(g => g.select(".domain").remove());
        const xAxis = g => g
            .attr("transform", `translate(0,${height - margin.bottom})`)
            .call(d3.axisBottom(x).ticks(width / 100)
                .tickFormat(d => `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`)
                .tickSizeOuter(0));

        const color = d3.scaleOrdinal()
            .domain(data.columns.slice(1))
            .range(d3.schemeCategory10);

        const area = d3.area()
            .x(d => x(d.data.date))
            .y0(d => y(d[0]))
            .y1(d => y(d[1]));

        const svg = d3.select("#" + id).append("svg")
            .attr("viewBox", [0, 0, width, height]);

        svg.append("g")
            .selectAll("path")
            .data(series)
            .join("path")
            .attr("fill", ({
                key
            }) => color(key))
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
        addLegend(svg, color, config, Object.keys(config.data), width, height);
    }

    function barChart(id, config) {
        const barMargin = {...margin, left: config.yAxisWidth || 50, bottom: config.xAxisHeight || 50};
        const height = config.height || 400;
        const width = config.width || 640;
        const color = d3.scaleOrdinal()
            .domain(Object.keys(config.data))
            .range(d3.schemeCategory10);
        const svg = d3.select("#" + id).append("svg")
            .attr("viewBox", [0, 0, width, height]);

        const yValues = Object.values(config.data).map(({y}) => y.map(Number))
            .flatMap(x => x);
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
            .attr("transform", `translate(${barMargin.left},0)`)
            .call(d3.axisLeft(y).tickValues(yAxisTicks))
            .call(g => g.select(".domain").remove())
            .call(g => g.append("text")
                .attr("x", -barMargin.left)
                .attr("y", 10)
                .attr("fill", "currentColor")
                .attr("text-anchor", "start"));

        const xValues = Object.values(config.data)[0].x;
        const x = d3.scaleBand()
            .domain(d3.range(xValues.length))
            .range([barMargin.left, width - barMargin.right])
            .padding(0.1);

        const interval = Math.ceil(xValues.length / 40);
        const xAxis = g => g
            .attr("transform", `translate(0,${height - barMargin.bottom})`)
            .call(d3.axisBottom(x)
                .tickValues(x.domain().filter((_, i) => i % interval === 0))
                .tickFormat(i => xValues[i])
                .tickSizeOuter(0));

        for(const [seriesTitle, seriesData] of Object.entries(config.data)) {
            const data = seriesData.x.reduce((acc, value, index) => {
                acc.push({
                    [seriesTitle]: value,
                    value: Number(seriesData.y[index])
                });
                return acc;
            }, []);

            let barColors;
            if (config.barColors) {
                barColors = d3.scaleOrdinal()
                    .domain(seriesData.x)
                    .range(config.barColors);
            } else {
                barColors = () => color(seriesTitle);
            }

            if (seriesData.type === "line") {
                const line = d3.line()
                    .x((d, i) => x(i))
                    .y(({value}) => value < 0 ? y(0) : y(value));

                svg.append("path")
                    .datum(data)
                    .attr("fill", "none")
                    .attr("stroke", barColors())
                    .attr("stroke-width", 1.5)
                    .attr("stroke-linejoin", "round")
                    .attr("stroke-linecap", "round")
                    .attr("d", line);
            } else {
                const tooltip = d3.select("#" + id)
                    .append("div")
                    .attr("class", "chart-tooltip")
                    .style("display", "none");

                const mouseAction = (event, {value}) => {
                    tooltip.style("display", "")
                        .text(value)
                        .style("left", (d3.pointer(event, document.body)[0]) + "px")
                        .style("top", Math.max(d3.pointer(event, document.body)[1] - 32, 0) + "px");
                };

                var mouseleave = () => {
                    tooltip.style("display", "none");
                };

                svg.append("g")
                    .selectAll("rect")
                    .data(data)
                    .join("rect")
                    .attr("fill", ({[seriesTitle]: d}) => barColors(d))
                    .attr("stroke-width", 1)
                    .attr("stroke", () => config.barBorder ? "black" : "")
                    .attr("x", (d, i) => x(i))
                    .attr("y", ({value}) => value < 0 ? y(0) : y(value))
                    .attr("height", d => Math.abs(y(0) - y(d.value)))
                    .attr("width", x.bandwidth())
                    .on("mouseover", mouseAction)
                    .on("mousemove", mouseAction)
                    .on("mouseleave", mouseleave);
            }
        }

        svg.append("g")
            .call(xAxis)
            .selectAll("text")
            .attr("transform", "rotate(-90)")
            .attr("y", -5)
            .attr("x", -6)
            .style("text-anchor", "end");

        svg.append("g")
            .call(yAxis);
        addTitle(svg, config, width);
        addLegend(svg, color, config, Object.keys(config.data), width, height);
    }

    function pieChart(id, config) {
        const pieMargin = {...margin, top: 40};
        let total = 0;
        const data = config.data.reduce((acc, value, index) => {
            acc.push({name: config.labels[index], value});
            total += Number(value);
            return acc;
        }, []);
        const height = (config.height || 400) - pieMargin.top;
        const width = config.width || 660;

        const svg = d3.select("#" + id).append("svg")
            .attr("viewBox", [-width / 2, -(height + pieMargin.top) / 2, width, height + pieMargin.top]);

        if (data.some(({value}) => value !== 0 && value !== null)) {
            const pie = d3.pie()
                .sort(null)
                .value(d => d.value);
            const arc = d3.arc()
                .innerRadius(0)
                .outerRadius(Math.min(width, height) / 2 - 1);

            const color = d3.scaleOrdinal()
                .domain(config.labels)
                .range(d3.schemeCategory10);

            const arcs = pie(data);

            const radius = Math.min(width, height) / 2 * 0.8;
            const arcLabel = d3.arc().innerRadius(radius)
                .outerRadius(radius);

            svg.append("g")
                .selectAll("path")
                .data(arcs)
                .join("path")
                .attr("fill", d => color(d.data.name))
                .attr("d", arc)
                .append("title")
                .text(d => `${d.data.name}`);

            svg.append("g")
                .attr("text-anchor", "middle")
                .selectAll("text")
                .data(arcs)
                .join("text")
                .attr("transform", d => `translate(${arcLabel.centroid(d)})`)
                .call(text => text.attr("fill", "currentColor").append("tspan")
                    .attr("y", "-0.4em")
                    .attr("font-weight", "bold")
                    .text(d => d3.format(",.2f")((d.data.value / total) * 100)));
            addLegend(svg, color, config, config.labels, width, height, (-width / 2) + 10, -height / 2 + pieMargin.top);
        }

        addTitle(svg, config, width, 0, (-height / 2) - 4);
    }

    return {
        stackedAreaChart,
        barChart,
        pieChart,
    };
})();