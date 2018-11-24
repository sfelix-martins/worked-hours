# Worked Hours

A project to calculate the worked hours in a day.

Just pass the `day`, `start`, `pause`, `continue` and `finish` times to calculate the worked hours.

## Usage

Clone the project:

```bash
git clone git@github.com:sfelix-martins/worked-hours.git
```

Access the project root folder:

```bash
cd worked-hours
```

And run the command like the following example passing the param in the order: `day`, `start`, `pause`, `continue` and `finish`:

```bash
php app worked:hours 2018-11-23 09:00 12:00 13:00 18:00
```

You should see something like:

```bash
+------------+----------+----------+----------+----------+-------------------+
| Day        | Start    | Pause    | Continue | Finish   | Total             |
+------------+----------+----------+----------+----------+-------------------+
| 2018-11-23 | 09:00:00 | 12:00:00 | 13:00:00 | 18:00:00 | You worked: 8 hrs |
+------------+----------+----------+----------+----------+-------------------+
``` 

## Contributing

Feels free to contribute and suggest!