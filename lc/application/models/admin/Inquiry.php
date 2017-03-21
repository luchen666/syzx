<?php

include_once(MODELS_PATH . "/BaseModel.php");

////////////////////////////////// Inquiry  ///////////////////////////////////////////////
class Inquiry Extends BaseModel
{
	public static function getInquiry()
	{
		$sql =  "SELECT	*  FROM  Inquiry WHERE isDeleted=0 ";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//查一个Inquiry
	public static function getoneInquiry($id)
	{
		$sql =  "SELECT *  FROM  Inquiry WHERE ID = :ID ";
		$rs = ExecSQL($sql,Array("ID"=>$id));
		return $rs->AsArray();
	}
	//新增订单
	public static function getNewInquiry(){
		$sql = "SELECT	COUNT(ID) as newInquiryNumber  FROM  Inquiry WHERE IsDeleted=0 and OrderDate > DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//查Inquiry各状态数量
	public static function getInquiryNumber($page=1,$rows = 10)
	{
		$from = ($page - 1)*$rows;
		$sql =  "SELECT State,Count(ID) AS Onumber FROM Inquiry WHERE IsDeleted=0 GROUP BY State WITH ROLLUP LIMIT $from,$rows";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
/*

		public override int Delete(List<int> IdList)
		{
			int result = 0;
			if (IdList != null && IdList.Count > 0)
			{
				var where = PredicateBuilder.True<Inquiry>();
				where = where.And(q => 1 == 2);
				foreach (var Id in IdList)
				{
					where = where.Or(q => q.InquiryID == Id);
				}

				var list = GetAll().Where(where);

				foreach (var entity in list)
				{
					entity.IsDelete = Shipping.Const.CommonConst.ISDELETE_YES;
				}
				result = Context.SaveChanges();
			}
			return result;
		}

		/// <summary>
		/// 获取询价单信息
		/// </summary>
		/// <returns></returns>
		public List<InquiryModel> GetInquiryInfo(InquiryModel.Search inquirySearch)
		{
			StringBuilder stringBuilder = new StringBuilder();
			stringBuilder.Append(" SELECT ir.InquiryID,ir.OriginatorId,ir.InquiryType, ir.palletUserId, ir.palletUserName, ir.palletId, ir.parlletName, ir.Freight, ir.FreightUnit, ir.QuantityTon, ir.QuantityPiece, ir.shipId, ir.shipName, ir.shipScheduleId, ir.shipUserName, ir.shipUserId, ir.State, ir.LoadTon, ir.CreatedByUserId, ir.CreatedByUserName, ir.CreatedDateTime, ir.LastUpdatedByUserId, ir.LastUpdatedByUserName, ir.LastUpdatedDateTime, ir.IsDelete, ");
			stringBuilder.Append(" 				p.`Name` as palletName , p.Type as palletType, p.PackagingType, p.Quantity, p.Unit, p.Freight, p.FreightUnit, p.QuantityTon, p.QuantityPiece, p.FromPortId, p.FromPortName, p.ToPortId, p.ToPortName, p.StartDateMin, p.StartDateMax, p.EndDateMin, p.EndDateMax, p.TransportRequirement, p.Matters, p.PaymentType, p.IsNeedAgent, p.IsNeedInvoice, ");
			stringBuilder.Append(" 				s.AgentId, s.AgentName, s.`Name` as shipname, s.ShipTypeId, s.TonScopeId, s.ShipMMSI, s.LoadTon, s.LoadPiece, s.PortOfRegistry, s.ShipLong, s.ShipWidth, s.ShipDeep, s.ShipCapacity, s.LoadDraft, s.EmptyDraft, s.HatchNum, s.MakeDate, s.ShipLogo, s.Star, s.ShipCertificate, ");
			stringBuilder.Append(" 				ss.EmptyPortId, ss.FromPortId, ss.ToPortId, ss.EmptyPortName, ss.FromPortName, ss.ToPortName, ss.PalletDescription, ss.ScheduledStartDateTime, ss.ScheduledEndDateTime, ss.StartDateTime, ss.EndDateTime, ss.Memo ");
			stringBuilder.Append(" from inquiry ir ");
			stringBuilder.Append(" INNER JOIN pallet p on p.PalletId=ir.palletId and p.UserId=ir.palletUserId and p.IsDelete=0 ");
			stringBuilder.Append(" INNER JOIN ship s on s.ShipId=ir.shipId and s.UserId=ir.shipUserId and s.IsDelete=0 ");
			stringBuilder.Append(" INNER JOIN shipschedule ss on ss.ShipScheduleId=ir.shipScheduleId and ss.IsDelete=0 ");
			stringBuilder.Append(" WHERE 1=1 ");
			stringBuilder.Append(" AND ir.IsDelete=0  ");
			#region 查询条件

			if (inquirySearch.InquiryID > 0)
			{
				stringBuilder.Append(" AND ir.InquiryID=?InquiryID  ");
			}
			if (inquirySearch.OriginatorId != null && (int)inquirySearch.OriginatorId > 0)
			{
				stringBuilder.Append(" AND ir.OriginatorId=?OriginatorId  ");
			}
			if (inquirySearch.palletId != null && (int)inquirySearch.palletId > 0)
			{
				stringBuilder.Append(" AND ir.palletId=?palletId  ");
			}
			if (inquirySearch.palletUserId != null && (int)inquirySearch.palletUserId > 0)
			{
				stringBuilder.Append(" AND ir.palletUserId=?palletUserId  ");
			}
			if (inquirySearch.shipId != null && (int)inquirySearch.shipId > 0)
			{
				stringBuilder.Append(" AND ir.shipId=?shipId  ");
			}
			if (inquirySearch.shipUserId != null && (int)inquirySearch.shipUserId > 0)
			{
				stringBuilder.Append(" AND ir.shipUserId=?shipUserId  ");
			}
			if (inquirySearch.shipScheduleId != null && (int)inquirySearch.shipScheduleId > 0)
			{
				stringBuilder.Append(" AND ir.shipScheduleId=?shipScheduleId  ");
			}
			#endregion
			stringBuilder.Append(" ORDER BY ir.LastUpdatedDateTime desc ");

			object[] parameters = {
				DbParameterHelper.MakeInParam("?InquiryID", (DbType)MySqlDbType.Int32,11, inquirySearch.InquiryID) ,
				DbParameterHelper.MakeInParam("?OriginatorId", (DbType)MySqlDbType.Int32,11, inquirySearch.OriginatorId) ,
				DbParameterHelper.MakeInParam("?palletId", (DbType)MySqlDbType.Int32,11, inquirySearch.palletId) ,
				DbParameterHelper.MakeInParam("?palletUserId", (DbType)MySqlDbType.Int32,11, inquirySearch.palletUserId) ,
				DbParameterHelper.MakeInParam("?shipId", (DbType)MySqlDbType.Int32,11, inquirySearch.shipId) ,
				DbParameterHelper.MakeInParam("?shipUserId", (DbType)MySqlDbType.Int32,11, inquirySearch.shipUserId) ,
				DbParameterHelper.MakeInParam("?shipScheduleId", (DbType)MySqlDbType.Int32,11, inquirySearch.shipScheduleId) ,
			};

			return Context.SqlQuery<InquiryModel>(stringBuilder.ToString(), parameters).ToList();
		}

		/// <summary>
		/// 查询 当前用户 已报价的货盘
		/// </summary>
		/// <returns></returns>
		public List<UsersType> InquiryPalletList(int userId)
		{
			try
			{
				StringBuilder stringBuilder = new StringBuilder();
				stringBuilder.Append(" SELECT DISTINCT i.palletId UsersTypeId,i.palletId UserType,i.palletId Description ");
				stringBuilder.Append(" from inquiry i ");
				stringBuilder.Append(" LEFT JOIN pallet p ");
				stringBuilder.Append(" on i.palletId = p.palletId ");
				stringBuilder.Append(" where 1=1 ");
				stringBuilder.Append(" and p.IsDelete=0 ");
				stringBuilder.Append(" and i.IsDelete=0 ");
				stringBuilder.Append(" and i.palletUserId=?userid ");

				object[] parameters = {
				DbParameterHelper.MakeInParam("?userid", (DbType)MySqlDbType.Int32,11, userId) ,
			};

				return Context.SqlQuery<UsersType>(stringBuilder.ToString(), parameters).ToList();
			}
			catch (Exception ex)
			{
				throw ex;
			}

		}


		/// <summary>
		/// 查询 当前用户 已报价的船期
		/// </summary>
		/// <returns></returns>
		public List<UsersType> InquiryList(int userId)
		{
			try
			{
				StringBuilder stringBuilder = new StringBuilder();
				stringBuilder.Append(" SELECT DISTINCT i.shipScheduleId UsersTypeId,i.shipScheduleId UserType,i.shipScheduleId Description ");
				stringBuilder.Append(" from inquiry i ");
				stringBuilder.Append(" LEFT JOIN shipschedule s ");
				stringBuilder.Append(" on i.shipScheduleId = s.ShipScheduleId ");
				stringBuilder.Append(" where 1=1 ");
				stringBuilder.Append(" and s.IsDelete=0 ");
				stringBuilder.Append(" and i.IsDelete=0 ");
				stringBuilder.Append(" and i.shipUserId=?userid ");

				object[] parameters = {
				DbParameterHelper.MakeInParam("?userid", (DbType)MySqlDbType.Int32,11, userId) ,
			};

				return Context.SqlQuery<UsersType>(stringBuilder.ToString(), parameters).ToList();
			}
			catch (Exception ex)
			{
				throw ex;
			}

		}


		/// <summary>
		/// 查询 当前用户 已报价的船期
		/// </summary>
		/// <returns></returns>
		public List<Inquiry> GetScheduleInquiryList(int ShipScheduleIdId)
		{
			try
			{
				StringBuilder stringBuilder = new StringBuilder();
				stringBuilder.Append(" SELECT * ");
				stringBuilder.Append(" from inquiry i ");
				stringBuilder.Append(" LEFT JOIN shipschedule s ");
				stringBuilder.Append(" on i.shipScheduleId = s.ShipScheduleId ");
				stringBuilder.Append(" where 1=1 ");
				stringBuilder.Append(" and s.IsDelete=0 ");
				stringBuilder.Append(" and i.IsDelete=0 ");
				stringBuilder.Append(" and i.shipScheduleId=?scheduleid ");

				object[] parameters = {
				DbParameterHelper.MakeInParam("?scheduleid", (DbType)MySqlDbType.Int32,11, ShipScheduleIdId) ,
			};

				return Context.SqlQuery<Inquiry>(stringBuilder.ToString(), parameters).ToList();
			}
			catch (Exception ex)
			{
				throw ex;
			}

		}

*/

		/// <summary>
		/// 查询已生成合同的货盘吨数
		/// </summary>
		/// <returns></returns>
		public static function GetLoadTon($palletId)
		{
			if(!is_Integer($palletId))
			{
				error_log("InquiryModel->GetLoadTon 参数不是整型：$palletId");
				return NULL;
			}
		
			$sql = "SELECT * from inquiry i LEFT JOIN contract c on c.InquiryID = i.InquiryID " . 
				   "where i.palletId = :palletId and i.state = 5";

			$parm = array("palletId" => palletId);
			$rs = ExecSQL($parm);
			
			return $rs->AsArray();
		}
/*
		#region 询报价 查询 移动

		/// <summary>
		/// 查询 当前用户  已报价的货盘
		/// </summary>
		/// <returns></returns>
		public List<PalletListModel> GetInquiryByPallet(int userId)
		{
			try
			{
				StringBuilder stringBuilder = new StringBuilder();
				stringBuilder.Append(" SELECT p.PalletId,p.`Name` as PalletName,p.Unit,p.Freight,p.QuantityTon,p.FromPortId,p.FromPortName,p.ToPortId,p.ToPortName,p.IsNeedAgent,p.IsNeedInvoice,p.StartDateMin,p.EndDateMax, ");
				stringBuilder.Append(" 			Count(i.palletId) as InquiryCount,s.UserId,s.`Name` as UserName,s.Avatar ");
				stringBuilder.Append(" from inquiry i  ");
				stringBuilder.Append(" inner join pallet p on i.palletId=p.PalletId and i.palletUserId=p.UserId and p.IsDelete=0 ");
				stringBuilder.Append(" LEFT JOIN users s on i.palletUserId=s.UserId and s.IsDelete=0 ");
				stringBuilder.Append(" where 1=1  ");
				stringBuilder.Append(" and i.IsDelete=0  ");
				//stringBuilder.Append(" and i.InquiryType='ship'  ");
				stringBuilder.Append(" and i.InquiryID not in(select c.InquiryID from contract c where c.IsDelete=0 and c.InquiryID = i.InquiryID) ");
				stringBuilder.Append(" and i.palletUserId=?userid ");
				stringBuilder.Append(" GROUP BY i.palletId  ");
				stringBuilder.Append(" ORDER BY i.LastUpdatedDateTime desc ");

				object[] parameters = {
				DbParameterHelper.MakeInParam("?userid", (DbType)MySqlDbType.Int32,11, userId) ,
			};

				return Context.SqlQuery<PalletListModel>(stringBuilder.ToString(), parameters).ToList();
			}
			catch (Exception ex)
			{
				throw ex;
			}

		}

		/// <summary>
		/// 查询 当前用户  已报价的货盘
		/// 报价货盘
		/// </summary>
		/// <returns></returns>
		public List<ShipScheduleMoble> GetInquiryByPalletDetail(int userId, int palletId)
		{
			try
			{
				StringBuilder stringBuilder = new StringBuilder();
				stringBuilder.Append(" SELECT t.ShipScheduleId,t.UserId,t.UserName,t.EmptyPortId,t.FromPortId,t.ToPortId,t.EmptyPortName,t.FromPortName,t.ToPortName,t.ScheduledStartDateTime,t.ScheduledEndDateTime,t.PreTonnage,t.EmptyDateTime,t.LastUpdatedDateTime,t.CreatedDateTime, ");
				stringBuilder.Append(" 			  s.ShipId,s.`Name` as ShipName,s.ShipTypeId,s.ShipMMSI,i.LoadTon,i.State,i.InquiryID,s.ShipLogo,st.ShipTypeName,COUNT(i.shipScheduleId)  ");
				stringBuilder.Append(" FROM  inquiry i ");
				stringBuilder.Append(" INNER JOIN   shipschedule t on t.ShipScheduleId=i.shipScheduleId and t.IsDelete=0 ");
				stringBuilder.Append(" INNER JOIN ship s on s.ShipId=t.ShipId and s.IsDelete=0	");
				stringBuilder.Append(" LEFT JOIN shiptype st on s.ShipTypeId=st.ShipTypeId   ");
				stringBuilder.Append(" where 1=1  ");
				stringBuilder.Append(" and i.IsDelete=0 ");
				//stringBuilder.Append(" and i.InquiryType='ship' ");
				stringBuilder.Append(" and i.InquiryID not in(select c.InquiryID from contract c where c.IsDelete=0 and c.InquiryID = i.InquiryID) ");
				stringBuilder.Append(" and i.palletUserId=?userId ");
				stringBuilder.Append(" and i.palletId=?palletId ");
				stringBuilder.Append(" GROUP BY i.shipScheduleId  ");
				stringBuilder.Append(" ORDER BY i.LastUpdatedDateTime DESC ");

				object[] parameters = {
					DbParameterHelper.MakeInParam("?userId", (DbType)MySqlDbType.Int32,11, userId) ,
					DbParameterHelper.MakeInParam("?palletId", (DbType)MySqlDbType.Int32,11, palletId) ,
				};

				return Context.SqlQuery<ShipScheduleMoble>(stringBuilder.ToString(), parameters).ToList();
			}
			catch (Exception ex)
			{
				throw ex;
			}

		}

		/// <summary>
		///  查询 当前用户 已报价的船期
		/// </summary>
		/// <returns></returns>
		public List<ShipScheduleMoble> GetInquiryByShip(int userId)
		{
			try
			{
				StringBuilder stringBuilder = new StringBuilder();
				stringBuilder.Append(" SELECT t.ShipScheduleId,t.UserId,t.UserName,t.AgentName,t.EmptyPortId,t.FromPortId,t.ToPortId,t.EmptyPortName,t.FromPortName,t.ToPortName,t.ScheduledStartDateTime,t.ScheduledEndDateTime,t.PreTonnage,t.EmptyDateTime,t.LastUpdatedDateTime,t.CreatedDateTime, ");
				stringBuilder.Append(" 		  s.ShipId,s.`Name` as ShipName,s.ShipTypeId,s.ShipMMSI,s.LoadTon,s.ShipLogo,st.ShipTypeName,COUNT(i.shipScheduleId)");
				stringBuilder.Append(" FROM  inquiry i ");
				stringBuilder.Append(" INNER JOIN   shipschedule t on t.ShipScheduleId=i.shipScheduleId and t.IsDelete=0 ");
				stringBuilder.Append(" INNER JOIN ship s on s.ShipId=t.ShipId and s.IsDelete=0	");
				stringBuilder.Append(" LEFT JOIN shiptype st on s.ShipTypeId=st.ShipTypeId   ");
				stringBuilder.Append(" where 1=1  ");
				stringBuilder.Append(" and i.IsDelete=0 ");
				//stringBuilder.Append(" and i.InquiryType='pallet' ");
				stringBuilder.Append(" and i.InquiryID not in(select c.InquiryID from contract c where c.IsDelete=0 and c.InquiryID = i.InquiryID) ");
				stringBuilder.Append(" and i.shipUserId=?userid ");
				stringBuilder.Append(" GROUP BY i.shipScheduleId ");
				stringBuilder.Append(" ORDER BY i.LastUpdatedDateTime DESC ");

				object[] parameters = {
				DbParameterHelper.MakeInParam("?userid", (DbType)MySqlDbType.Int32,11, userId) ,
			};

				return Context.SqlQuery<ShipScheduleMoble>(stringBuilder.ToString(), parameters).ToList();
			}
			catch (Exception ex)
			{
				throw ex;
			}

		}

		/// <summary>
		/// 查询 当前用户 已报价的船期
		/// 报价船期
		/// </summary>
		/// <returns></returns>
		public List<PalletListModel> GetInquiryByShipDetail(int userId, int ShipUserId)
		{
			try
			{
				StringBuilder stringBuilder = new StringBuilder();
				stringBuilder.Append(" SELECT p.PalletId,p.`Name` as PalletName,p.Freight,p.QuantityTon,p.FromPortId,p.FromPortName,p.ToPortId,p.ToPortName,p.IsNeedAgent,p.IsNeedInvoice,p.StartDateMin,p.EndDateMax, ");
				stringBuilder.Append(" 		  s.UserId,s.`Name` as UserName,s.Avatar,COUNT(i.palletId),i.InquiryID,i.State,i.LoadTon ");
				stringBuilder.Append(" from inquiry i  ");
				stringBuilder.Append(" inner join pallet p on i.palletId=p.PalletId and i.palletUserId=p.UserId and p.IsDelete=0 ");
				stringBuilder.Append(" LEFT JOIN users s on i.palletUserId=s.UserId and s.IsDelete=0 ");
				stringBuilder.Append(" where 1=1  ");
				stringBuilder.Append(" and i.IsDelete=0  ");
				//stringBuilder.Append(" and i.InquiryType='pallet'  ");
				stringBuilder.Append(" and i.InquiryID not in(select c.InquiryID from contract c where c.IsDelete=0 and c.InquiryID = i.InquiryID)  ");
				stringBuilder.Append(" and i.shipUserId=?userId ");
				stringBuilder.Append(" and i.shipScheduleId=?ShipUserId ");
				stringBuilder.Append(" GROUP BY p.PalletId ");
				stringBuilder.Append(" ORDER BY i.LastUpdatedDateTime desc ");

				object[] parameters = {
					DbParameterHelper.MakeInParam("?userId", (DbType)MySqlDbType.Int32,11, userId) ,
					DbParameterHelper.MakeInParam("?ShipUserId", (DbType)MySqlDbType.Int32,11, ShipUserId) ,
				};

				return Context.SqlQuery<PalletListModel>(stringBuilder.ToString(), parameters).ToList();
			}
			catch (Exception ex)
			{
				throw ex;
			}

		}
		#endregion


		/// <summary>
		/// 获取最新一条询价单信息
		/// </summary>
		/// <returns></returns>
		public Inquiry GetNewInquiry()
		{
			StringBuilder stringBuilder = new StringBuilder();
			stringBuilder.Append(" SELECT *  ");
			stringBuilder.Append(" from inquiry i ");
			stringBuilder.Append(" ORDER BY i.InquiryID DESC LIMIT 0,1 ");

			return Context.SqlQuery<Inquiry>(stringBuilder.ToString()).FirstOrDefault();
		}



		/// <summary>
		/// 异步获取询价单(联表)
		/// </summary>
		/// <returns></returns>
		public Task<List<AdminInquiryModel>> GetAdminInquiryModelListAsync()
		{
			Task<List<AdminInquiryModel>> list = null;
			try
			{
				StringBuilder Sql = new StringBuilder();
				Sql.Append(" SELECT													  ");
				Sql.Append(" 				iy.InquiryID,										");
				Sql.Append(" 				iy.OriginatorId,									 ");
				Sql.Append(" 				iy.InquiryType,									  ");
				Sql.Append(" 				iy.palletUserId,									 ");
				Sql.Append(" 				iy.palletUserName,								   ");
				Sql.Append(" 				iy.palletId,										 ");
				Sql.Append(" 				iy.parlletName,									  ");
				Sql.Append(" 				iy.Freight,										  ");
				Sql.Append(" 				iy.FreightUnit,									  ");
				Sql.Append(" 				iy.QuantityTon,									  ");
				Sql.Append(" 				iy.QuantityPiece,									");
				Sql.Append(" 				iy.shipId,										   ");
				Sql.Append(" 				iy.shipName,										 ");
				Sql.Append(" 				iy.shipScheduleId,								   ");
				Sql.Append(" 				iy.shipUserName,									 ");
				Sql.Append(" 				iy.shipUserId,									   ");
				Sql.Append(" 				iy.State,											");
				Sql.Append(" 				iy.LoadTon,										  ");
				Sql.Append(" 				iy.CreatedByUserId,								  ");
				Sql.Append(" 				iy.CreatedByUserName,								");
				Sql.Append(" 				iy.CreatedDateTime,								  ");
				Sql.Append(" 				iy.LastUpdatedByUserId,							  ");
				Sql.Append(" 				iy.LastUpdatedByUserName,							");
				Sql.Append(" 				iy.LastUpdatedDateTime,							  ");
				Sql.Append(" 				iy.IsDelete,										 ");
				Sql.Append(" 				u.`Name` AS OriginatorName,											");
				Sql.Append(" 				ss.EmptyPortName,									");
				Sql.Append(" 				ss.FromPortName,									 ");
				Sql.Append(" 				ss.ToPortName,									   ");
				Sql.Append(" 				ss.ScheduledStartDateTime,						   ");
				Sql.Append(" 				ss.EndDateTime									   ");
				Sql.Append(" FROM Inquiry iy											 ");
				Sql.Append("  		LEFT JOIN users u									  ");
				Sql.Append("  							ON iy.OriginatorId=u.UserId				  ");
				Sql.Append(" 					LEFT JOIN ShipSchedule ss						  ");
				Sql.Append(" 										ON iy.shipScheduleId=ss.shipScheduleId   ");
				Sql.AppendFormat(" WHERE iy.IsDelete={0}										 ",Const.CommonConst.ISDELETE_NO);
				Sql.Append(" ORDER BY  iy.InquiryID DESC								 ");

				list = Task.FromResult(Context.SqlQuery<AdminInquiryModel>(Sql.ToString()).ToList());

			}
			catch (Exception ex)
			{
				
				throw ex;
			}
			return list;

		}


		/// <summary>
		/// 获取询价单(联表)
		/// </summary>
		/// <param name="Id">ID</param>
		/// <returns></returns>
		public AdminInquiryModel GetAdminInquiryModel(int Id)
		{
		   AdminInquiryModel model = null;
			try
			{
				StringBuilder Sql = new StringBuilder();
				Sql.Append(" SELECT													  ");
				Sql.Append(" 				iy.InquiryID,										");
				Sql.Append(" 				iy.OriginatorId,									 ");
				Sql.Append(" 				iy.InquiryType,									  ");
				Sql.Append(" 				iy.palletUserId,									 ");
				Sql.Append(" 				iy.palletUserName,								   ");
				Sql.Append(" 				iy.palletId,										 ");
				Sql.Append(" 				iy.parlletName,									  ");
				Sql.Append(" 				iy.Freight,										  ");
				Sql.Append(" 				iy.FreightUnit,									  ");
				Sql.Append(" 				iy.QuantityTon,									  ");
				Sql.Append(" 				iy.QuantityPiece,									");
				Sql.Append(" 				iy.shipId,										   ");
				Sql.Append(" 				iy.shipName,										 ");
				Sql.Append(" 				iy.shipScheduleId,								   ");
				Sql.Append(" 				iy.shipUserName,									 ");
				Sql.Append(" 				iy.shipUserId,									   ");
				Sql.Append(" 				iy.State,											");
				Sql.Append(" 				iy.LoadTon,										  ");
				Sql.Append(" 				iy.CreatedByUserId,								  ");
				Sql.Append(" 				iy.CreatedByUserName,								");
				Sql.Append(" 				iy.CreatedDateTime,								  ");
				Sql.Append(" 				iy.LastUpdatedByUserId,							  ");
				Sql.Append(" 				iy.LastUpdatedByUserName,							");
				Sql.Append(" 				iy.LastUpdatedDateTime,							  ");
				Sql.Append(" 				iy.IsDelete,										 ");
				Sql.Append(" 				u.`Name` AS OriginatorName,											");
				Sql.Append(" 				ss.EmptyPortName,									");
				Sql.Append(" 				ss.FromPortName,									 ");
				Sql.Append(" 				ss.ToPortName,									   ");
				Sql.Append(" 				ss.ScheduledStartDateTime,						   ");
				Sql.Append(" 				ss.EndDateTime									   ");
				Sql.Append(" FROM Inquiry iy											 ");
				Sql.Append("  		LEFT JOIN users u									  ");
				Sql.Append("  							ON iy.OriginatorId=u.UserId				  ");
				Sql.Append(" 					LEFT JOIN ShipSchedule ss						  ");
				Sql.Append(" 										ON iy.shipScheduleId=ss.shipScheduleId   ");
				Sql.AppendFormat(" WHERE iy.IsDelete={0}										 ", Const.CommonConst.ISDELETE_NO);
				Sql.AppendFormat("	   AND iy.InquiryID={0}										 ", Id);
				Sql.Append(" ORDER BY  iy.InquiryID DESC								 ");

				model = Context.SqlQuery<AdminInquiryModel>(Sql.ToString()).FirstOrDefault();

			}
			catch (Exception ex)
			{

				throw ex;
			}
			return model;

		}
	}
	*/

    ////////////////////////////////// Pollbill  /////////////////////////

//	public static function getPoolbill()
//	{
//		$sql =  "SELECT	*  FROM  pay_poolbill ";
//		$rs = ExecSQL($sql);
//		return $rs->AsArray();
//	}
	//查一个Poolbill
//	public static function getonePoolbill($id)
//	{
//		$sql =  "SELECT *  FROM  pay_poolbill WHERE ID = :ID ";
//		$rs = ExecSQL($sql,Array("ID"=>$id));
//		return $rs->AsArray();
//	}
	////////////////////////////////// Userbill  /////////////////////////
//	public static function getUserbill()
//	{
//		$sql =  "SELECT	*  FROM  pay_userbill ";
//		$rs = ExecSQL($sql);
//		return $rs->AsArray();
//	}
//	////////////////////////////////// Requestlog  /////////////////////////
//	public static function getRequestlog()
//	{
//		$sql =  "SELECT	*  FROM  pay_requestlog ";
//		$rs = ExecSQL($sql);
//		return $rs->AsArray();
//	}
	////////////////////////////////// Responselog  /////////////////////////
//	public static function getResponselog()
//	{
//		$sql =  "SELECT	*  FROM  pay_responselog ";
//		$rs = ExecSQL($sql);
//		return $rs->AsArray();
//	}


	//在Inquiry表中删除选中记录
	public static function del($id)
	{
		$id = explode(",",$id);
		$count = count($id);
		for($i=0;$i<$count;$i++){
			ExecSQL("UPDATE Inquiry SET IsDeleted = 1 WHERE ID = '$id[$i]'");
		}
	}

	//获取数据记录总个数
	public static function getCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM Inquiry");
		return $rs->Cnt;
	}

	//======================== 资金流水池 ======================
	//资金流水池列表
	public static function getPoolbill($page=1,$rows=20,$where=array())
	{	$from = ($page-1)*$rows;
		$sql = "SELECT PB.* , US.Name
				FROM pay_poolbill AS PB LEFT JOIN sys_users AS US ON PB.UserID = US.UserID %s
				LIMIT $from,$rows";
		if(IsSet($where["Name"]) && $where["Name"] != "")
		{	$text = "WHERE US.Name LIKE '%" . $where["Name"] ."%'";
			$sql = sprintf($sql,$text);
		}
		else    $sql = sprintf($sql, "WHERE IsDeleted =0");
		$rs = ExecSQL($sql);
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}

	//获取资金流水池数据记录总个数
	public static function getPoolbillCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM pay_poolbill");
		return $rs->Cnt;
	}
	//=====================客户流水======================
	public static function getUserbill($page=1,$rows=20,$where=array())
	{	$from  = ($page-1)*$rows;
		$sql = "SELECT UB.* , US.Name
				FROM pay_userbill AS UB LEFT JOIN sys_users AS US ON UB.UserID = US.UserID %s
				LIMIT $from,$rows";
		if(IsSet($where["Name"]) && $where["Name"] !="")
		{
			$text = "WHERE US.Name LIKE '%" . $where["Name"] ."%'";
			$sql = sprintf($sql,$text);
		}
		else	$sql = sprintf($sql,"");
		$rs = ExecSQL($sql);
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}
	public static function getUserbillCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM pay_userbill");
		return $rs->Cnt;
	}
	//==========================网关日志================
	public static function getRequestlog($page=1,$rows=20,$where=array())
	{	$from  = ($page-1)*$rows;
		$sql = "SELECT RL.* , US.Name
				FROM pay_requestlog AS RL LEFT JOIN sys_users AS US ON RL.UserID = US.UserID %s
				LIMIT $from,$rows";
		if(IsSet($where["Name"]) && $where["Name"] !="")
		{
			$text = "WHERE US.Name LIKE '%" . $where["Name"] ."%'";
			$sql = sprintf($sql,$text);
		}
		else	$sql = sprintf($sql,"");
		$rs = ExecSQL($sql);
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;

	}
	public static function getReqLogCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM pay_requestlog");
		return $rs->Cnt;
	}

	//=====================交易通知================
	public static function getResLog($page=1,$rows)
	{
		$from = ($page-1)*$rows;
		$rs =  ExecSQL("SELECT	*  FROM  pay_responselog LIMIT $from,$rows");
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}

	public  static function getReslogCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM pay_responselog");
		return $rs->Cnt;
	}
}