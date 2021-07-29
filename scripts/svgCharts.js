/* global d3 */
/* exported barChart, stackedAreaChart */

const {barChart, stackedAreaChart} = (function () {
    const margin = ({
        top: 20,
        right: 45,
        bottom: 30,
        left: 30
    });

    function addTitle(svg, config, width) {
        svg.append("text")
            .attr("x", ((config.width || width) / 2))
            .attr("y", (margin.top / 2) + 3)
            .attr("font-size", "16px")
            .attr("text-anchor", "middle")
            .attr("fill", "currentColor")
            .text(config.title);
    }

    function addLegend(svg, color, config, isBar, width, height) {
        if (!isBar) {
            svg.selectAll("seriesColor")
                .data(Object.keys(config.data))
                .enter()
                .append("circle")
                .attr("cx", margin.left + (config.axisLeft ? 30 : 10))
                .attr("cy", (d,i) => margin.left + 10 + i * 25)
                .attr("r", 7)
                .style("fill", d => color(d));
        }

        const series = svg.selectAll("series")
            .data(Object.keys(config.data))
            .enter()
            .append("text")
            .attr("fill", "currentColor");

        if (isBar) {
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
        } else {
            series .attr("x", margin.left + (config.axisLeft ? 55 : 25))
                .attr("y", (d,i) => 45 + i * 25);
        }
        series.text(d => d);
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
        addLegend(svg, color, config, false, width, height);
    }

    function barChart(id, config) {
        const barMargin = {...margin, left: 50, bottom: config.xAxisHeight || 50};
        const seriesTitle = Object.keys(config.data)[0];
        const data = config.data[seriesTitle].x.reduce((acc, value, index) => {
            acc.push({[seriesTitle]: value, value: parseInt(config.data[seriesTitle].y[index], 10)});
            return acc;
        }, []);
        const height = config.height || 400;
        const width = config.width || 640;

        const x = d3.scaleBand()
            .domain(d3.range(data.length))
            .range([barMargin.left, width - barMargin.right])
            .padding(0.1);

        const interval = Math.ceil(data.length / 40);

        const xAxis = g => g
            .attr("transform", `translate(0,${height - barMargin.bottom})`)
            .call(d3.axisBottom(x)
                .tickValues(x.domain().filter((_, i) => i % interval === 0))
                .tickFormat(i => data[i][seriesTitle])
                .tickSizeOuter(0));

        const y = d3.scaleLinear()
            .domain([0, d3.max(data, d => d.value)])
            .nice()
            .range([height - barMargin.bottom, barMargin.top]);

        const yInterval = config.yAxisTickCount ? Math.ceil(y.ticks().length / config.yAxisTickCount) : 1;
        const yAxisTicks = y.ticks().filter(Number.isInteger)
            .filter((_, i) => i % yInterval === 0);

        const yAxis = g => g
            .attr("transform", `translate(${barMargin.left},0)`)
            .call(d3.axisLeft(y).tickValues(yAxisTicks)
                .tickFormat(d3.format("d")))
            .call(g => g.select(".domain").remove())
            .call(g => g.append("text")
                .attr("x", -barMargin.left)
                .attr("y", 10)
                .attr("fill", "currentColor")
                .attr("text-anchor", "start")
                .text(data.y));

        const svg = d3.select("#" + id).append("svg")
            .attr("viewBox", [0, 0, width, height]);

        const color = d3.scaleOrdinal()
            .domain([seriesTitle])
            .range(d3.schemeCategory10);

        let barColors;
        if (config.barColors) {
            barColors = d3.scaleOrdinal()
                .domain(config.data[seriesTitle].x)
                .range(config.barColors);
        } else {
            barColors = () => color(seriesTitle);
        }

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
            .attr("y", d => y(d.value))
            .attr("height", d => y(0) - y(d.value))
            .attr("width", x.bandwidth())
            .on("mouseover", mouseAction)
            .on("mousemove", mouseAction)
            .on("mouseleave", mouseleave);

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
        addLegend(svg, color, config, true, width, height);
    }

    return {
        stackedAreaChart,
        barChart,
    };
})();