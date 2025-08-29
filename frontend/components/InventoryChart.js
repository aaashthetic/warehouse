import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  Title,
  Legend,
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  Title,
  Legend
);

import {
  BarChart,
  Bar,
  LineChart,
  Line,
  XAxis,
  YAxis,
  Cell,
  Tooltip,
  CartesianGrid,
  ResponsiveContainer,
  Legend as RechartsLegend,
} from "recharts";

export default function InventoryChart({ products, inventory, demand, rfidLogs }) {
    const renderCustomTooltip = ({ active, payload }) => {
        if (active && payload && payload.length) {
        const { name, stock, alert } = payload[0].payload;
        return (
            <div className="bg-gray-800 p-2 shadow rounded text-white">
            <p className="font-semibold">{name}</p>
            <p>Stock: {stock}</p>
            </div>
        );
        }
        return null;
    };

    // Custom legend
    const renderCustomLegend = () => {
        const items = [
        { label: "No Stock", color: "#9ca3af" },
        { label: "Limited Stock", color: "#ef4444" },
        { label: "Low Stock", color: "#f97316" },
        { label: "In Stock", color: "#22c55e" },
        ];
        return (
        <div className="flex justify-center gap-6 mt-2 text-black">
            {items.map((item, index) => (
            <div key={index} className="flex items-center gap-1 text-sm">
                <span
                className="inline-block w-3 h-3 rounded"
                style={{ backgroundColor: item.color }}
                ></span>
                {item.label}
            </div>
            ))}
        </div>
        );
    };

    // Merge inventory with product names
    const mergedInventory = inventory
    .map((ia) => {
        const product = products.find((p) => p.sku === ia.sku);
        return {
        ...ia,
        product_name: product ? product.product_name : ia.sku, // fallback if not found
        };
    })
    .sort((a, b) => a.sku.localeCompare(b.sku)); // ensure ascending SKU order

    // Group by SKU and sum stock
    const groupedInventory = mergedInventory.reduce((acc, item) => {
        if (!acc[item.sku]) {
        acc[item.sku] = { ...item };
        } else {
        acc[item.sku].stock += item.stock;
        }
        return acc;
    }, {});

    const sortedInventory = Object.values(groupedInventory).sort((a, b) =>
        a.sku.localeCompare(b.sku)
    );
    
    if (!products || products.length === 0) return;

    // Build base dataset with all products (reserve space even for 0 stock)
    const baseData = products.map((p) => ({
        sku: p.sku,
        product_name: p.product_name,
        stock: 0, // default to 0
    }));

    // Fill in actual inventory stock
    const barChartData = baseData.map((p) => {
    const inv = sortedInventory.find((item) => item.sku === p.sku);
    const stock = inv ? inv.stock : 0;
    return {
        name: p.product_name,
        stock,
        fill:
        stock === 0
            ? "#000000" // Black - No Stock
            : stock <= 10
            ? "#ef4444" // Red - Limited Stock
            : stock <= 30
            ? "#f97316" // Orange - Low Stock
            : "#22c55e", // Green - In Stock
    };
    });

    // Demand chart (sorted by months)
    const monthOrder = [
        "January","February","March","April","May","June",
        "July","August","September","October","November","December"
    ];

    const sortedDemand = [...demand].sort(
        (a, b) => monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month)
    );

    // Merge RFID logs with product names
    const mergedLogs = rfidLogs.map((log) => {
    const product = products.find((p) => p.sku === log.sku);
    return {
        ...log,
        product_name: product ? product.product_name : log.sku,
    };
    });

    // Take latest 5 merged logs
    const recentLogs = [...mergedLogs]
    .sort((a, b) => new Date(b.last_scanned) - new Date(a.last_scanned))
    .slice(0, 5);

  return (
    <div className="space-y-10 p-6">
      {/* Charts Row */}
      <div className="flex gap-6">
        {/* Bar Chart */}
        <div className="w-1/2 bg-white p-4 rounded-2xl shadow">
          <h2 className="text-lg font-semibold mb-2 text-gray-800">
            Inventory Stock Levels
          </h2>
            <ResponsiveContainer width="100%" height={300}>
            <BarChart data={barChartData}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="name" />
                <YAxis />
                <Tooltip content={renderCustomTooltip} />
                <RechartsLegend content={renderCustomLegend} />
                <Bar dataKey="stock">
                {barChartData.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={entry.fill} />
                ))}
                </Bar>
            </BarChart>
            </ResponsiveContainer>
        </div>

        {/* Line Chart */}
        <div className="w-1/2 bg-white p-4 rounded-2xl shadow">
          <h2 className="text-lg font-semibold mb-2 text-gray-800">
            Predicted Demand
          </h2>
            <ResponsiveContainer width="100%" height={300}>
            <LineChart data={sortedDemand}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="month" tickFormatter={(v) => v.substring(0, 3)} />
              <YAxis />
              <Tooltip
                    labelStyle={{ color: "#ffffffff" }}
                    itemStyle={{ color: "#959595ff" }}
                    contentStyle={{ backgroundColor: "#1f1f1fff", border: "1px solid #000", borderRadius: "5px" }}
                />
              <Line
                type="monotone"
                dataKey="sales"
                stroke="#3b82f6"
                strokeWidth={2}
                dot={{ r: 3 }}
              />
            </LineChart>
            </ResponsiveContainer>
        </div>
        </div>

        {/* Table */}
        <div className="bg-white shadow p-4 rounded-xl">
            <h2 className="text-lg font-bold mb-4 text-gray-800">
            Latest RFID Scan Logs
            </h2>
            <table className="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr className="bg-gray-100 text-gray-800">
                <th className="border border-gray-300 px-2 py-1">SKU</th>
                <th className="border border-gray-300 px-2 py-1">Product Name</th>
                <th className="border border-gray-300 px-2 py-1">Location</th>
                <th className="border border-gray-300 px-2 py-1">Last Scanned</th>
                <th className="border border-gray-300 px-2 py-1">Status</th>
                </tr>
            </thead>
            <tbody>
                {recentLogs.map((log, idx) => (
                <tr key={idx} className="text-gray-800">
                    <td className="border border-gray-300 px-2 py-1">{log.sku}</td>
                    <td className="border border-gray-300 px-2 py-1">{log.product_name}</td>
                    <td className="border border-gray-300 px-2 py-1">{log.location}</td>
                    <td className="border border-gray-300 px-2 py-1">{log.last_scanned}</td>
                    <td className="border border-gray-300 px-2 py-1">{log.status}</td>
                </tr>
                ))}
            </tbody>
            </table>
        </div>

        {/* Heatmap Placeholder */}
        <div className="bg-white shadow p-4 rounded-xl">
            <h2 className="text-lg font-bold mb-4 text-gray-800">
            Product Movement Density (Metabase)
            </h2>
            <iframe
            src="http://localhost:3001/public/question/b83ce88e-de0d-4093-97cf-0c10f6366bbd"
            width="100%"
            height="400"
            frameBorder="0"
            />
        </div>

        {/* Metabase Dashboard */}
        <div className="bg-white shadow p-4 rounded-xl">
            <h2 className="text-lg font-bold mb-4 text-gray-800">
                Metabase Dashboard
            </h2>
            <div className="w-full h-[80vh]">
                <iframe
                src="http://localhost:3001/public/dashboard/6cc351a4-560e-44b2-8960-db9b719e4903"
                className="w-full h-full"
                frameBorder="0"
                />
            </div>
        </div>
    </div>
  );
}