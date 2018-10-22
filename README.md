# Snowflake id Generator
That library implements snowflake id generation algorithm in php.

### Usage

```php
$settings = SnowflakeGeneratorSettings::newInstance()->setNodeId(1);
$generator = SnowflakeGenerator($settings);
$id = $generator->getNext();
```
For more examples check project *examples* folder

### How do I get set up? ###
```
composer require highqualityfriends/snowflake
```


### Benchmark 

1 subjects, 5 iterations, 10,000 revs, 0 rejects, 0 failures, 0 warnings

(best [mean mode] worst) = 0.686 [0.712 0.704] 0.750 (μs)
⅀T: 3.559μs μSD/r 0.021μs μRSD/r: 3.006%


| benchmark                | subject       | revs  | iter | mem_peak   | time_rev | comp_z_value | comp_deviation |
|--------------------------|---------------|-------|------|------------|----------|--------------|----------------|
| SnowflakeGeneratorBench  | benchGetNext  | 10000 | 0    | 1,033,184b | 0.686μs  | -1.22σ       | -3.68%         |
| SnowflakeGeneratorBench  | benchGetNext  | 10000 | 1    | 1,033,184b | 0.709μs  | -0.14σ       | -0.43%         |
| SnowflakeGeneratorBench  | benchGetNext  | 10000 | 2    | 1,033,184b | 0.750μs  | |1.78σ       | 5.36%          |
| SnowflakeGeneratorBench  | benchGetNext  | 10000 | 3    | 1,033,184b | 0.700μs  | -0.54σ       | -1.62%         |
| SnowflakeGeneratorBench  | benchGetNext  | 10000 | 4    | 1,033,184b | 0.714μs  | |0.12σ       | 0.37%          |
