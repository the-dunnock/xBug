<?php
$_lang['xbug.mysql.select_type.simple'] = '<p>Simple SELECT (not using UNION or subqueries)</p>';
$_lang['xbug.mysql.select_type.primary'] = '<p>Outermost SELECT</p>';
$_lang['xbug.mysql.select_type.union'] = '<p>Second or later SELECT statement in a UNION</p>';
$_lang['xbug.mysql.select_type.dependent_union'] = '<p>Second or later SELECT statement in a UNION, dependent on outer query</p>';
$_lang['xbug.mysql.select_type.union_result'] = '<p>Result of a UNION.</p>';
$_lang['xbug.mysql.select_type.subquery'] = '<p>First SELECT in subquery</p>';
$_lang['xbug.mysql.select_type.dependent_subquery'] = '<p>First SELECT in subquery, dependent on outer query</p>';
$_lang['xbug.mysql.select_type.derived'] = '<p>Derived table SELECT (subquery in FROM clause)</p>';
$_lang['xbug.mysql.select_type.materialized'] = '<p>Materialized subquery</p>';
$_lang['xbug.mysql.select_type.uncacheable_subquery'] = '<p>A subquery for which the result cannot be cached and must be re-evaluated for each row of the outer query</p>';
$_lang['xbug.mysql.select_type.uncacheable_union'] = '<p>The second or later select in a UNION that belongs to
        an uncacheable subquery (see UNCACHEABLE SUBQUERY)</p>';

$_lang['xbug.mysql.type.system'] = '<p>The table has only one row (= system table). This is a special case of the const join type.</p>';
$_lang['xbug.mysql.type.const'] = '<p>The table has at most one matching row, which is read at the start of
    the query. Because there is only one row, values from the column in this row can be regarded as
    constants by the rest of the optimizer. const tables are very fast because they are read only once.</p>
    <p>const is used when you compare all parts of a PRIMARY KEY or UNIQUE index to constant values.</p>';
$_lang['xbug.mysql.type.eq_ref'] = '<p>One row is read from this table for each combination of rows from the previous tables. Other than the system and const types,
    this is the best possible join type. It is used when all parts of an index are used by the join and the
    index is a PRIMARY KEY or UNIQUE NOT NULL index.</p>
    <p>eq_ref can be used for indexed columns that are compared using the = operator.
    The comparison value can be a constant or an expression that uses columns from tables that are read before this table</p>';
$_lang['xbug.mysql.type.ref'] = '<p>All rows with matching index values are read from this table for each combination of rows from the previous tables. ref is used if the join uses only a leftmost prefix of the key or if the key is not a PRIMARY KEY or UNIQUE index (in other words, if the join cannot select a single row based on the key value). If the key that is used matches only a few rows, this is a good join type.</p>
<p>ref can be used for indexed columns that are compared using the = or <=> operator.</p>';
$_lang['xbug.mysql.type.fulltext'] = '<p>The join is performed using a FULLTEXT index.</p>';
$_lang['xbug.mysql.type.ref_or_null'] = '<p>This join type is like ref, but with the addition that MySQL does an extra search for rows that contain NULL values. This join type optimization is used most often in resolving subqueries.</p>';
$_lang['xbug.mysql.type.index_merge'] = '<p>This join type indicates that the Index Merge optimization is used. In this case, the key column in the output row contains a list of indexes used, and key_len contains a list of the longest key parts for the indexes used.</p>';
$_lang['xbug.mysql.type.unique_subquery'] = '<p>This type replaces ref for some IN subqueries of the following form:</p>
    <p><code>value IN (SELECT primary_key FROM single_table WHERE some_expr)</code></p>
    <p>unique_subquery is just an index lookup function that replaces the subquery completely for better efficiency.</p>';
$_lang['xbug.mysql.type.index_subquery'] = '<p>This join type is similar to unique_subquery. It replaces IN subqueries, but it works for nonunique indexes in subqueries of the following form:</p>
    <p><code>value IN (SELECT key_column FROM single_table WHERE some_expr)</code></p>';
$_lang['xbug.mysql.type.range'] = '<p>Only rows that are in a given range are retrieved, using an index to select the rows. The key column in the output row indicates which index is used. The key_len contains the longest key part that was used. The ref column is NULL for this type.</p>
<p>range can be used when a key column is compared to a constant using any of the =, <>, >, >=, <, <=, IS NULL, <=>, BETWEEN, or IN() operators</p>';
$_lang['xbug.mysql.type.index'] = '<p>The index join type is the same as ALL, except that the index tree is scanned. This occurs two ways:</p>
<ul>
    <li>If the index is a covering index for the queries and can be used to satisfy all data required from the table, only the index tree is scanned. In this case, the Extra column says Using index. An index-only scan usually is faster than ALL because the size of the index usually is smaller than the table data.</li>
    <li>A full table scan is performed using reads from the index to look up data rows in index order. Uses index does not appear in the Extra column.</li>
</ul>
<p>MySQL can use this join type when the query uses only columns that are part of a single index.</p>';
$_lang['xbug.mysql.type.all'] = '<p>A full table scan is done for each combination of rows from the previous tables. This is normally not good if the table is the first table not marked const, and usually very bad in all other cases. Normally, you can avoid ALL by adding indexes that enable row retrieval from the table based on constant values or column values from earlier tables.</p>';

$_lang['xbug.mysql.extra.const_row_not_found'] = '<p>For a query such as SELECT ... FROM tbl_name, the table was empty.</p>';
$_lang['xbug.mysql.extra.deleting_all_rows'] = '<p>For DELETE, some storage engines (such as MyISAM) support a handler method that removes all table rows in a simple and fast way. This Extra value is displayed if the engine uses this optimization.</p>';
$_lang['xbug.mysql.extra.distinct'] = '<p>MySQL is looking for distinct values, so it stops searching for more rows for the current row combination after it has found the first matching row.</p>';
$_lang['xbug.mysql.extra.firstmatch'] = '<p>The semi-join FirstMatch join shortcutting strategy is used for tbl_name.</p>';
$_lang['xbug.mysql.extra.full_scan_on_null_key'] = '<p>This occurs for subquery optimization as a fallback strategy when the optimizer cannot use an index-lookup access method.</p>';
$_lang['xbug.mysql.extra.impossible_having'] = '<p>The HAVING clause is always false and cannot select any rows.</p>';
$_lang['xbug.mysql.extra.impossible_where'] = '<p>The WHERE clause is always false and cannot select any rows.</p>';
$_lang['xbug.mysql.extra.impossible_where_noticed_after_reading_const_table'] = '<p>MySQL has read all const (and system) tables and notice that the WHERE clause is always false.</p>';
$_lang['xbug.mysql.extra.loosescan'] = '<p>The semi-join LooseScan strategy is used. m and n are key part numbers.</p>';
$_lang['xbug.mysql.extra.materialize_scan'] = '<p>Before MySQL 5.6.7, this indicates use of a single materialized temporary table. If Scan is present, no temporary table index is used for table reads. Otherwise, an index lookup is used. See also the Start materialize entry.</p>
<p>As of MySQL 5.6.7, materialization is indicated by rows with a select_type value of MATERIALIZED and rows with a table value of &lt;subqueryN&gt;</p>';
$_lang['xbug.mysql.extra.no_matching_min_max_row'] = '<p>No row satisfies the condition for a query such as SELECT MIN(...) FROM ... WHERE condition.</p>';
$_lang['xbug.mysql.extra.no_matching_row_in_const_table'] = '<p>For a query with a join, there was an empty table or a table with no rows satisfying a unique index condition.</p>';
$_lang['xbug.mysql.extra.no_matching_rows_after_partition_pruning'] = '<p>For DELETE or UPDATE, the optimizer found nothing to delete or update after partition pruning. It is similar in meaning to Impossible WHERE for SELECT statements.</p>';
$_lang['xbug.mysql.extra.no_tables_used'] = '<p>The query has no FROM clause, or has a FROM DUAL clause.</p>
<p>For INSERT or REPLACE statements, EXPLAIN displays this value when there is no SELECT part. For example, it appears for EXPLAIN INSERT INTO t VALUES(10) because that is equivalent to EXPLAIN INSERT INTO t SELECT 10 FROM DUAL.</p>';
$_lang['xbug.mysql.extra.not_exists'] = '<p>MySQL was able to do a LEFT JOIN optimization on the query and does not examine more rows in this table for the previous row combination after it finds one row that matches the LEFT JOIN criteria. Here is an example of the type of query that can be optimized this way:</p>
<p><code>SELECT * FROM t1 LEFT JOIN t2 ON t1.id=t2.id
        WHERE t2.id IS NULL;</code></p>
<p>Assume that t2.id is defined as NOT NULL. In this case, MySQL scans t1 and looks up the rows in t2 using the values of t1.id. If MySQL finds a matching row in t2, it knows that t2.id can never be NULL, and does not scan through the rest of the rows in t2 that have the same id value. In other words, for each row in t1, MySQL needs to do only a single lookup in t2, regardless of how many rows actually match in t2.</p>';
$_lang['xbug.mysql.extra.range_checked_for_each_record'] = '<p>MySQL found no good index to use, but found that some of indexes might be used after column values from preceding tables are known. For each row combination in the preceding tables, MySQL checks whether it is possible to use a range or index_merge access method to retrieve rows. This is not very fast, but is faster than performing a join with no index at all. The applicability criteria are as described in Section 8.2.1.3, “Range Optimization”, and Section 8.2.1.4, “Index Merge Optimization”, with the exception that all column values for the preceding table are known and considered to be constants.</p>
<p>Indexes are numbered beginning with 1, in the same order as shown by SHOW INDEX for the table. The index map value N is a bitmask value that indicates which indexes are candidates. For example, a value of 0x19 (binary 11001) means that indexes 1, 4, and 5 will be considered.</p>';
$_lang['xbug.mysql.extra.scanned_n_databases'] = '<p>This indicates how many directory scans the server performs when processing a query for INFORMATION_SCHEMA tables, as described in Section 8.2.4, “Optimizing INFORMATION_SCHEMA Queries”. The value of N can be 0, 1, or all.</p>';
$_lang['xbug.mysql.extra.select_tables_optimized_away'] = '<p>The query contained only aggregate functions (MIN(), MAX()) that were all resolved using an index, or COUNT(*) for MyISAM, and no GROUP BY clause. The optimizer determined that only one row should be returned.</p>';
$_lang['xbug.mysql.extra.skip_open_table'] = '<p>These values indicate file-opening optimizations that apply to queries for INFORMATION_SCHEMA tables, as described in Section 8.2.4, “Optimizing INFORMATION_SCHEMA Queries”.</p>
<p>Table files do not need to be opened. The information has already become available within the query by scanning the database directory.</p>';
$_lang['xbug.mysql.extra.open_frm_only'] = '<p>These values indicate file-opening optimizations that apply to queries for INFORMATION_SCHEMA tables, as described in Section 8.2.4, “Optimizing INFORMATION_SCHEMA Queries”.</p>
<p>Only the table\'s .frm file need be opened.</p>';
$_lang['xbug.mysql.extra.open_trigger_only'] = '<p>These values indicate file-opening optimizations that apply to queries for INFORMATION_SCHEMA tables, as described in Section 8.2.4, “Optimizing INFORMATION_SCHEMA Queries”.</p>
<p>Only the table\'s .TRG file need be opened.</p>';
$_lang['xbug.mysql.extra.open_full_table'] = '<p>These values indicate file-opening optimizations that apply to queries for INFORMATION_SCHEMA tables, as described in Section 8.2.4, “Optimizing INFORMATION_SCHEMA Queries”.</p>
<p>The unoptimized information lookup. The .frm, .MYD, and .MYI files must be opened.</p>';
$_lang['xbug.mysql.extra.start_materialize_end_materialize_scan'] = '<p>Before MySQL 5.6.7, this indicates use of multiple materialized temporary tables. If Scan is present, no temporary table index is used for table reads. Otherwise, an index lookup is used. See also the Materialize entry.</p>
<p>As of MySQL 5.6.7, materialization is indicated by rows with a select_type value of MATERIALIZED and rows with a table value of &lt;subqueryN&gt;.</p>';
$_lang['xbug.mysql.extra.start_temporary_end_temporary'] = '<p>This indicates temporary table use for the semi-join Duplicate Weedout strategy.</p>';
$_lang['xbug.mysql.extra.unique_row_not_found'] = '<p>For a query such as SELECT ... FROM tbl_name, no rows satisfy the condition for a UNIQUE index or PRIMARY KEY on the table.</p>';
$_lang['xbug.mysql.extra.using_filesort'] = '<p>MySQL must do an extra pass to find out how to retrieve the rows in sorted order. The sort is done by going through all rows according to the join type and storing the sort key and pointer to the row for all rows that match the WHERE clause. The keys then are sorted and the rows are retrieved in sorted order. See Section 8.2.1.15, “ORDER BY Optimization”.</p>';
$_lang['xbug.mysql.extra.using_index'] = '<p>The column information is retrieved from the table using only information in the index tree without having to do an additional seek to read the actual row. This strategy can be used when the query uses only columns that are part of a single index.</p>
<p>If the Extra column also says Using where, it means the index is being used to perform lookups of key values. Without Using where, the optimizer may be reading the index to avoid reading data rows but not using it for lookups. For example, if the index is a covering index for the query, the optimizer may scan it without using it for lookups.</p>
<p>For InnoDB tables that have a user-defined clustered index, that index can be used even when Using index is absent from the Extra column. This is the case if type is index and key is PRIMARY.</p>';
$_lang['xbug.mysql.extra.using_index_condition'] = '<p>Tables are read by accessing index tuples and testing them first to determine whether to read full table rows. In this way, index information is used to defer (“push down”) reading full table rows unless it is necessary. See Section 8.2.1.6, “Index Condition Pushdown Optimization”.</p>';
$_lang['xbug.mysql.extra.using_index_for_group_by'] = '<p>Similar to the Using index table access method, Using index for group-by indicates that MySQL found an index that can be used to retrieve all columns of a GROUP BY or DISTINCT query without any extra disk access to the actual table. Additionally, the index is used in the most efficient way so that for each group, only a few index entries are read. For details, see Section 8.2.1.16, “GROUP BY Optimization”.</p>';
$_lang['xbug.mysql.extra.using_join_buffer'] = '<p>Tables from earlier joins are read in portions into the join buffer, and then their rows are used from the buffer to perform the join with the current table. (Block Nested Loop) indicates use of the Block Nested-Loop algorithm and (Batched Key Access) indicates use of the Batched Key Access algorithm. That is, the keys from the table on the preceding line of the EXPLAIN output will be buffered, and the matching rows will be fetched in batches from the table represented by the line in which Using join buffer appears.</p>';
$_lang['xbug.mysql.extra.using_join_buffer_block_nested_loop_using_join_buffer_batched_key'] = '<p>Tables from earlier joins are read in portions into the join buffer, and then their rows are used from the buffer to perform the join with the current table. (Block Nested Loop) indicates use of the Block Nested-Loop algorithm and (Batched Key Access) indicates use of the Batched Key Access algorithm. That is, the keys from the table on the preceding line of the EXPLAIN output will be buffered, and the matching rows will be fetched in batches from the table represented by the line in which Using join buffer appears.</p>';
$_lang['xbug.mysql.extra.using_sort_union'] = '<p>These indicate how index scans are merged for the index_merge join type. See Section 8.2.1.4, “Index Merge Optimization”.</p>';
$_lang['xbug.mysql.extra.using_mrr'] = '<p>Tables are read using the Multi-Range Read optimization strategy. See Section 8.2.1.13, “Multi-Range Read Optimization”.</p>';
$_lang['xbug.mysql.extra.using_union'] = '<p>These indicate how index scans are merged for the index_merge join type. See Section 8.2.1.4, “Index Merge Optimization”.</p>';
$_lang['xbug.mysql.extra.using_sort_union'] = '<p>These indicate how index scans are merged for the index_merge join type. See Section 8.2.1.4, “Index Merge Optimization”.</p>';
$_lang['xbug.mysql.extra.using_intersect'] = '<p>These indicate how index scans are merged for the index_merge join type. See Section 8.2.1.4, “Index Merge Optimization”.</p>';
$_lang['xbug.mysql.extra.using_temporary'] = '<p>To resolve the query, MySQL needs to create a temporary table to hold the result. This typically happens if the query contains GROUP BY and ORDER BY clauses that list columns differently.</p>';
$_lang['xbug.mysql.extra.using_where'] = '<p>A WHERE clause is used to restrict which rows to match against the next table or send to the client. Unless you specifically intend to fetch or examine all rows from the table, you may have something wrong in your query if the Extra value is not Using where and the table join type is ALL or index.</p>';
$_lang['xbug.mysql.extra.using_where_with_pushed_condition'] = '<p>This item applies to NDB tables only. It means that MySQL Cluster is using the Condition Pushdown optimization to improve the efficiency of a direct comparison between a nonindexed column and a constant. In such cases, the condition is “pushed down” to the cluster\'s data nodes and is evaluated on all data nodes simultaneously. This eliminates the need to send nonmatching rows over the network, and can speed up such queries by a factor of 5 to 10 times over cases where Condition Pushdown could be but is not used. For more information, see Section 8.2.1.5, “Engine Condition Pushdown Optimization”.</p>';
